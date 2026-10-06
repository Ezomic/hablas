#!/bin/sh
# Builds a manifest of spoken Spanish test clips with macOS voices, in three
# conditions: a Spanish voice (the clean baseline), a Dutch voice reading the
# Spanish text (a learner's accent) and a Brazilian Portuguese voice reading
# it (a Portunol-style slip). Usage: make_test_clips.sh OUTDIR
set -e
out="${1:?output directory}"
mkdir -p "$out"
: > "$out/manifest.tsv"
i=0
while IFS= read -r text; do
    i=$((i + 1))
    for pair in "clean:Eddy (Spanish (Spain))" "dutch:Ellen" "portunol:Eddy (Portuguese (Brazil))"; do
        condition="${pair%%:*}"
        voice="${pair#*:}"
        file="$out/$condition-$i.wav"
        say -v "$voice" -o "$file" --data-format=LEI16@16000 "$text"
        printf '%s\t%s\t%s\n' "$file" "$condition" "$text" >> "$out/manifest.tsv"
    done
done <<'TEXTS'
Hola, me llamo Ana.
Buenos días, ¿cómo estás?
Quiero un café con leche, por favor.
La llave de la habitación.
¿Dónde está el banco?
Vivo en un piso pequeño en Madrid.
Mi hermano trabaja en una oficina.
Tengo veinte años.
El desayuno es a las ocho.
Hace sol y hace calor.
No me gusta el pescado.
Necesito un billete para el tren.
La cuenta, por favor.
Mucho gusto, me llamo Pablo.
Estoy cansado hoy.
¿Cuánto cuesta esta camiseta?
Voy a la estación en autobús.
Mi casa está cerca del parque.
Hay una farmacia al lado del banco.
Gracias, hasta luego.
TEXTS
echo "$i clips per condition in $out/manifest.tsv"
