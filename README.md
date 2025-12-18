# `replace-tags` script usage

This script helps replace placeholder tags in key files of this WordPress theme
template to quickly scaffold a new child theme.

The scripts folder may be deleted after use if desired.

## Requirements

- A POSIX-compatible shell (Git Bash, WSL, macOS, Linux).
- `perl` available in the PATH (used for safe multi-line/global replacements).

## Usage

Run interactively (Git Bash / WSL / macOS / Linux):

```bash
bash scripts/replace-tags.sh
```

Run from PowerShell using WSL (if WSL is installed):

```powershell
wsl bash scripts/replace-tags.sh
```

## Notes

- The script prompts for each placeholder and performs in-place replacements in the files:
  - `composer.json`
  - `functions.php`
  - `style.css`
  - `theme.json`
  - `README.md`
  - `LICENSE`
- Leave an input blank to skip replacing that tag.
- The script uses `perl` to correctly handle special characters and multi-line matches.
- Back up files (or use git) before running if you want an easy revert.

### Placeholders

- `%text-domain%`: The text domain for the theme in kebab case (e.g., `my-really-cool-theme`).
- `%description%`: Theme description.
- `%author-name%`: Author's name.
- `%author-email%`: Author's email.
- `%author-homepage%`: Author's homepage URL.
- `%year%`: Current year (auto-generated).
- `%function-name%`: Function name derived from text domain (not prompted).
- `%pascal-case%`: PascalCase version of text domain (not prompted).
