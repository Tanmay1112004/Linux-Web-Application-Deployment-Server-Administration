#!/bin/bash
set -euo pipefail

echo "[1/5] Updating packages..."
sudo dnf update -y

echo "[2/5] Installing Apache, PHP, MariaDB, Git and tools..."
sudo dnf install -y httpd php php-mysqli php-json mariadb105-server git curl unzip

echo "[3/5] Enabling services..."
sudo systemctl enable --now httpd
sudo systemctl enable --now mariadb

echo "[4/5] Preparing application directories..."
sudo mkdir -p /etc/student-portal
sudo mkdir -p /var/log/student-portal

echo "[5/5] Checking services..."
systemctl is-active --quiet httpd
systemctl is-active --quiet mariadb

echo "Installation complete."
echo "Next: configure /etc/student-portal/config.php and import database/database.sql."
