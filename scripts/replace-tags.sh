#!/usr/bin/env bash
set -euo pipefail

# Simple tag replacement script for this repo
# Scans a set of files and replaces placeholder tags with user-provided values.

FILES=(composer.json functions.php LICENSE README.md style.css theme.json)

TAGS=(
  "%text-domain%"
  "%pretty-name%"
  "%description%"
  "%author-name%"
  "%author-email%"
  "%author-homepage%"
  # "%function-name%" is generated from %text-domain%
  # "%pascal-case%" is generated from %text-domain%
)

echo "Scanning files: ${FILES[*]}"

# Auto-generate and replace %year% with the current year
year=$(date +%Y)
echo "Auto-replacing %year% -> ${year}"
for f in "${FILES[@]}"; do
  if [ -f "$f" ]; then
    REPL="$year" perl -0777 -i -pe 's/\Q%year%\E/$ENV{REPL}/g' "$f"
  fi
done

for tag in "${TAGS[@]}"; do
  if [ "${tag}" = "%text-domain%" ]; then
    # text-domain is required; keep prompting until provided
    value=""
    while [ -z "$value" ]; do
      read -rp "Replacement for ${tag} (required): " value
      if [ -z "$value" ]; then
        echo "Value required for ${tag}."
      fi
    done

    # generate function-name: replace non-alnum with underscore, collapse underscores
    function_name=$(echo "$value" | sed 's/[^a-zA-Z0-9]/_/g' | sed 's/_\+/_/g' | sed 's/^_//; s/_$//')

    # generate pascal-case: split on non-alnum, capitalize parts
    IFS=' ' read -r -a parts <<< "$(echo "$value" | sed 's/[^a-zA-Z0-9]/ /g')"
    pascal_case=""
    for p in "${parts[@]}"; do
      if [ -n "$p" ]; then
        first=$(echo "${p:0:1}" | tr '[:lower:]' '[:upper:]')
        rest=$(echo "${p:1}" | tr '[:upper:]' '[:lower:]')
        pascal_case+="${first}${rest}"
      fi
    done

    # perform replacements for text-domain, function-name, and pascal-case
    for f in "${FILES[@]}"; do
      if [ -f "$f" ]; then
        REPL="$value" perl -0777 -i -pe 's/\Q%text-domain%\E/$ENV{REPL}/g' "$f"
        REPL="$function_name" perl -0777 -i -pe 's/\Q%function-name%\E/$ENV{REPL}/g' "$f"
        REPL="$pascal_case" perl -0777 -i -pe 's/\Q%pascal-case%\E/$ENV{REPL}/g' "$f"
      else
        echo "Warning: $f not found, skipping." >&2
      fi
    done
    continue
  fi

  # For all other tags: require non-empty input
  value=""
  while [ -z "$value" ]; do
    read -rp "Replacement for ${tag} (required): " value
    if [ -z "$value" ]; then
      echo "Value required for ${tag}."
    fi
  done

  # Store some tag values for later use (README overwrite)
  if [ "${tag}" = "%pretty-name%" ]; then
    pretty_name="$value"
  elif [ "${tag}" = "%description%" ]; then
    description="$value"
  fi

  for f in "${FILES[@]}"; do
    if [ -f "$f" ]; then
      REPL="$value" perl -0777 -i -pe 's/\Q'"${tag}"'\E/$ENV{REPL}/g' "$f"
    else
      echo "Warning: $f not found, skipping." >&2
    fi
  done
done

# If pretty-name and description were provided, overwrite README.md
if [ -n "${pretty_name:-}" ] && [ -n "${description:-}" ]; then
  printf '# %s\n\n%s\n' "$pretty_name" "$description" > README.md
  echo "README.md written with pretty-name and description."
fi

echo "Done."
