#!/bin/bash
set -euo pipefail

PROJECT_ROOT="/var/www/html"
BACKUP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../backups" && pwd)"
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
OUTPUT="${BACKUP_DIR}/website_${TIMESTAMP}.tar.gz"

mkdir -p "$BACKUP_DIR"
sudo tar -czf "$OUTPUT" -C "$PROJECT_ROOT" .

echo "Website backup created: $OUTPUT"
