#!/bin/bash
set -euo pipefail

BACKUP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../backups" && pwd)"
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
OUTPUT="${BACKUP_DIR}/student_portal_${TIMESTAMP}.sql.gz"

mkdir -p "$BACKUP_DIR"

sudo mysqldump --single-transaction --routines --triggers student_portal | gzip > "$OUTPUT"

echo "Database backup created: $OUTPUT"
