import base64
import io
import json
import os
import sys

import numpy as np
import soundfile as sf


def main() -> int:
    requests = [json.loads(line) for line in sys.stdin if line.strip()]
    protocol = sys.stdout
    sys.stdout = sys.stderr

    from supertonic import TTS

    model_dir = os.environ.get("SPEECH_MODELS_DIR") or None
    tts = TTS(model_dir=model_dir, auto_download=True)
    styles = {}
    failed = 0

    for request in requests:
        try:
            voice = request["voice"]
            if voice not in styles:
                styles[voice] = tts.get_voice_style(voice_name=voice)
            wav, _ = tts.synthesize(
                request["text"],
                voice_style=styles[voice],
                lang=request["language"],
                speed=float(request["speed"]),
            )
            buffer = io.BytesIO()
            sf.write(buffer, np.asarray(wav, dtype=np.float32).reshape(-1), tts.sample_rate, format="WAV", subtype="PCM_16")
            result = {"id": request["id"], "wav": base64.b64encode(buffer.getvalue()).decode("ascii")}
        except Exception as error:
            failed += 1
            result = {"id": request.get("id"), "error": str(error)}
        protocol.write(json.dumps(result) + "\n")
        protocol.flush()

    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
