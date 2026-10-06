"""Transcribes spoken test clips with faster-whisper and scores them against what was meant.

Usage: evaluate.py MANIFEST.tsv [--model small] [--language es]

MANIFEST.tsv has one clip per line: path<TAB>condition<TAB>expected text. The
table printed at the end gives, per condition, how many clips came back
exactly right, right ignoring accents, and the mean word error, plus the
transcription speed. Local evaluation only; nothing here runs in production.
"""

import argparse
import re
import statistics
import sys
import time
import unicodedata

from faster_whisper import WhisperModel


def fold(text: str) -> list[str]:
    text = unicodedata.normalize("NFD", text.lower())
    text = "".join(c for c in text if unicodedata.category(c) != "Mn")
    return re.findall(r"[a-z0-9ñ]+", text)


def exact(text: str) -> list[str]:
    return re.findall(r"[\w]+", text.lower())


def word_errors(expected: list[str], heard: list[str]) -> float:
    rows = list(range(len(heard) + 1))
    for i, e in enumerate(expected, 1):
        prev, rows = rows, [i] + [0] * len(heard)
        for j, h in enumerate(heard, 1):
            rows[j] = min(prev[j] + 1, rows[j - 1] + 1, prev[j - 1] + (e != h))
    return rows[-1] / max(len(expected), 1)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("manifest")
    parser.add_argument("--model", default="small")
    parser.add_argument("--language", default="es")
    parser.add_argument("--threads", type=int, default=4)
    args = parser.parse_args()

    started = time.time()
    model = WhisperModel(args.model, device="cpu", compute_type="int8", cpu_threads=args.threads)
    print(f"model {args.model} loaded in {time.time() - started:.1f}s", file=sys.stderr)

    results: dict[str, list[tuple[bool, bool, float, float, float]]] = {}
    for line in open(args.manifest, encoding="utf-8"):
        if not line.strip():
            continue
        path, condition, expected = line.rstrip("\n").split("\t")
        began = time.time()
        segments, info = model.transcribe(path, language=args.language, beam_size=1, vad_filter=True)
        heard = " ".join(s.text.strip() for s in segments)
        took = time.time() - began
        wer = word_errors(fold(expected), fold(heard))
        results.setdefault(condition, []).append(
            (exact(expected) == exact(heard), wer == 0, wer, took, info.duration)
        )
        print(f"{condition:10} {expected!r} -> {heard!r}")

    print(f"\n{'condition':10} {'n':>3} {'exact':>6} {'no-accent':>10} {'mean WER':>9} {'sec/clip':>9} {'x realtime':>11}")
    for condition, rows in results.items():
        seconds = statistics.mean(r[3] for r in rows)
        length = statistics.mean(r[4] for r in rows)
        print(
            f"{condition:10} {len(rows):>3} {sum(r[0] for r in rows):>6} {sum(r[1] for r in rows):>10} "
            f"{statistics.mean(r[2] for r in rows):>9.2f} {seconds:>9.2f} {length / seconds:>11.1f}"
        )
    return 0


if __name__ == "__main__":
    sys.exit(main())
