#!/bin/bash
set -u

PASS=0
FAIL=0

check() {
    local name="$1"
    shift
    if "$@" >/dev/null 2>&1; then
        printf "[OK]   %s\n" "$name"
        PASS=$((PASS+1))
    else
        printf "[FAIL] %s\n" "$name"
        FAIL=$((FAIL+1))
    fi
}

check "Apache service" systemctl is-active httpd
check "MariaDB service" systemctl is-active mariadb
check "HTTP port 80" bash -c "ss -lnt | grep -q ':80 '"
check "MariaDB port 3306" bash -c "ss -lnt | grep -q ':3306 '"
check "Local HTTP response" curl -fsS http://localhost/

echo
echo "Summary: $PASS checks passed, $FAIL checks failed."

if [ "$FAIL" -gt 0 ]; then
    exit 1
fi
