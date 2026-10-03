import base64
import io
import json
import os
import sys
import tempfile

import numpy as np
import soundfile as sf

SAMPLE_RATE = 24000
EDGE_SILENCE_S = 0.08
SILENCE_THRESHOLD = 0.01
PEAK_DBFS = -3.0
FADE_IN_S = 0.005
FADE_OUT_S = 0.03
COMPRESSION_LEVEL = 0.6


def resample(audio: np.ndarray, source_rate: int) -> np.ndarray:
    if source_rate == SAMPLE_RATE:
        return audio
    target = int(round(len(audio) * SAMPLE_RATE / source_rate))
    spectrum = np.fft.rfft(audio)
    resized = np.zeros(target // 2 + 1, dtype=spectrum.dtype)
    kept = min(len(spectrum), len(resized))
    resized[:kept] = spectrum[:kept]
    return (np.fft.irfft(resized, target) * (target / len(audio))).astype(np.float32)


def trim(audio: np.ndarray) -> np.ndarray:
    voiced = np.where(np.abs(audio) > SILENCE_THRESHOLD * np.abs(audio).max())[0]
    pad = int(EDGE_SILENCE_S * SAMPLE_RATE)
    return audio[max(voiced[0] - pad, 0):min(voiced[-1] + pad, len(audio))]


def fade(audio: np.ndarray) -> np.ndarray:
    audio = audio.copy()
    head = min(int(FADE_IN_S * SAMPLE_RATE), len(audio))
    tail = min(int(FADE_OUT_S * SAMPLE_RATE), len(audio))
    audio[:head] *= np.linspace(0.0, 1.0, head, dtype=np.float32)
    audio[len(audio) - tail:] *= np.linspace(1.0, 0.0, tail, dtype=np.float32)
    return audio


def encode(wav: bytes) -> tuple[bytes, int]:
    audio, rate = sf.read(io.BytesIO(wav), dtype="float32", always_2d=True)
    audio = resample(audio.mean(axis=1).astype(np.float32), rate)
    peak = np.abs(audio).max()
    if peak == 0:
        raise ValueError("silent audio")
    audio = fade(trim(audio))
    audio = audio * (10 ** (PEAK_DBFS / 20) / np.abs(audio).max())

    handle, path = tempfile.mkstemp(suffix=".mp3")
    os.close(handle)
    try:
        with sf.SoundFile(path, "w", samplerate=SAMPLE_RATE, channels=1, format="MP3", subtype="MPEG_LAYER_III", bitrate_mode="CONSTANT", compression_level=COMPRESSION_LEVEL) as out:
            out.write(audio)
        with open(path, "rb") as file:
            return file.read(), int(round(len(audio) / SAMPLE_RATE * 1000))
    finally:
        os.unlink(path)


def main() -> int:
    failed = 0
    for line in sys.stdin:
        if not line.strip():
            continue
        request = {}
        try:
            request = json.loads(line)
            mp3, duration_ms = encode(base64.b64decode(request["wav"]))
            result = {"id": request["id"], "mp3": base64.b64encode(mp3).decode("ascii"), "duration_ms": duration_ms}
        except Exception as error:
            failed += 1
            result = {"id": request.get("id") if isinstance(request, dict) else None, "error": str(error)}
        sys.stdout.write(json.dumps(result) + "\n")
        sys.stdout.flush()
    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
