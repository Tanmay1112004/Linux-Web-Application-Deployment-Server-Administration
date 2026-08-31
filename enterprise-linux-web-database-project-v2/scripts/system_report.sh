#!/bin/bash
set -euo pipefail

echo "========================================"
echo " Linux Student Portal System Report"
echo " Generated: $(date)"
echo " Hostname : $(hostname)"
echo "========================================"
echo

echo "--- OS ---"
grep -E '^(NAME|VERSION)=' /etc/os-release || true
echo

echo "--- Uptime ---"
uptime
echo

echo "--- Memory ---"
free -h
echo

echo "--- Disk ---"
df -h /
echo

echo "--- Processes ---"
ps -eo pid,comm,%cpu,%mem --sort=-%cpu | head -n 8
echo

echo "--- Services ---"
systemctl is-active httpd || true
systemctl is-active mariadb || true
echo

echo "--- Listening Ports ---"
sudo ss -lntp | grep -E ':80 |:3306 ' || true
