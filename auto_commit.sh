# #!/bin/bash
# cd /path/to/your/project
# git add .
# git commit -m "Auto commit on $(date)"
# git push origin main
#!/bin/bash
cd #!/usr/bin/env bash
set -euo pipefail

REPO_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$REPO_DIR" || { echo "ERROR: cannot cd $REPO_DIR"; exit 1; }

# ensure we're in a git repo
if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  echo "ERROR: not a git repository: $REPO_DIR"; exit 1
fi

# determine current branch (fallback to master)
BRANCH="$(git symbolic-ref --short HEAD 2>/dev/null || git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "master")"
if [ -z "$BRANCH" ] || [ "$BRANCH" = "HEAD" ]; then
  echo "WARNING: HEAD is detached or branch unknown. Creating 'auto-commit' branch."
  BRANCH="auto-commit"
  git checkout -b "$BRANCH"
fi

echo "Repo: $REPO_DIR"
echo "Branch: $BRANCH"

# check for changes
if [ -n "$(git status --porcelain)" ]; then
  git add -A
  git commit -m "Auto commit on $(date '+%Y-%m-%d %H:%M:%S')"
  # ensure remote branch exists / set upstream if necessary
  if ! git ls-remote --exit-code --heads origin "$BRANCH" >/dev/null 2>&1; then
    echo "Remote branch origin/$BRANCH not found. Pushing and setting upstream..."
    git push -u origin "$BRANCH"
  else
    git pull --rebase origin "$BRANCH" || true
    git push origin "$BRANCH"
  fi
  echo "Pushed changes to origin/$BRANCH"
else
  echo "No changes to commit."
fi
git add .
git commit -m "Auto commit on $(date)"
git push origin main
