# Speech audio generation

Audio is generated on a Mac and delivered to the box by hand. Nothing here runs in production.

## Install

No ffmpeg is needed: MP3 encoding goes through libsndfile in the `soundfile` package.

```
python3 -m venv .venv-tts
.venv-tts/bin/pip install -r scripts/tts/requirements.txt
```

Add to `.env`:

```
SPEECH_PYTHON=.venv-tts/bin/python
SPEECH_MODELS_DIR=
```

`SPEECH_MODELS_DIR` is optional. Without it Supertonic caches its model in `~/.cache/supertonic3` and downloads it on first use.

## Run

```
php artisan speech:sample                 # one sentence per language and speed, in storage/app/private/speech-samples
php artisan speech:generate es --dry-run  # clip count and size estimate, writes nothing
php artisan speech:generate es            # both speeds, skips clips that already exist
php artisan speech:verify es              # lists strings without audio, fails if the language requires audio
php artisan speech:index                  # rebuild speech_clips rows from the files on disk
php artisan speech:prune                  # lists orphans; add --force to delete them
rsync -a --ignore-existing storage/app/public/speech/ <box>:/home/hablas/shared/speech/
```

`speech:generate` also takes `--voice=` (id or name, default is the primary voice), `--speed=normal|slow`, `--limit=` (texts, not clips) and `--force` (regenerate existing files).
