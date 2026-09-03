# Enterprise Linux Web & Database Administration Project 

A portfolio-grade Linux + AWS EC2 project that deploys a modern Student Management Portal and demonstrates practical Linux administration, networking, services, permissions, MariaDB, Bash automation, Cron, monitoring, backups, restore, logging, and troubleshooting.

## What changed in v2?

The original project was a good Linux administration lab. This version keeps that foundation but makes it more resume-worthy:

- Modern responsive dashboard UI
- Dashboard cards for total students, courses, cities and recent activity
- Search and filter students
- CRUD operations with validation
- Prepared SQL statements
- Separate database user with least privilege
- Centralized configuration outside the web root
- Security headers
- Apache + PHP + MariaDB deployment
- Bash health-check and system-report scripts
- Website + database backup scripts
- Cron automation
- Backup restore practice
- Troubleshooting runbook
- GitHub-ready structure with `.gitignore`
- Optional two-server AWS architecture documented for the next level

## Architecture

### Phase 1 — Single EC2 server

```text
                    INTERNET
                       |
                       v
              AWS Security Group
              22 (your IP)
              80 (internet)
                       |
                       v
              +------------------+
              | Amazon Linux 2023|
              |      EC2         |
              |                  |
              | Apache :80       |
              | PHP              |
              | MariaDB :3306    |
              +------------------+
                       |
                       v
             Student Management UI
                       |
                       v
              student_portal DB
```

### Phase 2 — Advanced two-server design

```text
Internet
   |
   v
Web EC2
Apache + PHP
Security Group: 22 from admin IP, 80/443 from internet
   |
   | TCP 3306
   v
DB EC2
MariaDB
Security Group: 3306 ONLY from Web Server SG
```

Do NOT expose database port 3306 to `0.0.0.0/0`.

## Technology Stack

- AWS EC2
- Amazon Linux 2023
- Apache HTTP Server
- PHP 8+
- MariaDB
- SQL
- Bash
- Cron
- systemd
- Git/GitHub
- curl
- ss
- journalctl

## Repository structure

```text
enterprise-linux-web-database-project-v2/
├── README.md
├── .gitignore
├── website/
│   ├── index.php
│   ├── students.php
│   ├── add.php
│   ├── edit.php
│   ├── delete.php
│   ├── health.php
│   ├── db.php
│   ├── config.example.php
│   ├── assets/
│   │   ├── app.js
│   │   └── style.css
│   └── partials/
│       ├── header.php
│       └── footer.php
├── database/
│   ├── database.sql
│   └── sample-data.sql
├── scripts/
│   ├── install_lamp.sh
│   ├── health_check.sh
│   ├── system_report.sh
│   ├── backup_website.sh
│   ├── backup_database.sh
│   └── restore_database.sh
├── docs/
│   ├── deployment.md
│   ├── troubleshooting.md
│   ├── interview-questions.md
│   └── resume-points.md
├── backups/
└── screenshots/
```

---

# Step-by-Step AWS + Amazon Linux Practice

## Step 0 — Launch EC2

Recommended:

- OS: Amazon Linux 2023
- Instance: t3.micro
- Storage: 8–10 GB
- Security Group:
  - SSH 22 → My IP
  - HTTP 80 → `0.0.0.0/0`
  - HTTPS 443 → optional for the HTTPS phase
- Do NOT add 3306 publicly.

Connect:

```bash
ssh -i your-key.pem ec2-user@PUBLIC_IP
```

Verify:

```bash
whoami
cat /etc/os-release
uname -a
free -h
df -h
```

## Step 1 — Update and install packages

Practice manually first:

```bash
sudo dnf update -y
sudo dnf install httpd php php-mysqli php-json mariadb105-server git curl unzip -y
```

Check:

```bash
httpd -v
php -v
mysql --version
git --version
```

## Step 2 — Start services

```bash
sudo systemctl enable --now httpd
sudo systemctl enable --now mariadb
```

Verify:

```bash
systemctl is-active httpd
systemctl is-enabled httpd
systemctl is-active mariadb
```

## Step 3 — Test Apache

```bash
curl -I http://localhost
sudo ss -lntp | grep :80
```

Open:

```text
http://PUBLIC_IP
```

If it fails, use:

```bash
sudo systemctl status httpd
sudo journalctl -u httpd -n 50 --no-pager
sudo tail -n 50 /var/log/httpd/error_log
```

## Step 4 — Secure MariaDB

Run:

```bash
sudo mysql_secure_installation
```

Then:

```bash
sudo mysql
```

Create database and application user:

```sql
CREATE DATABASE student_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'webapp'@'localhost' IDENTIFIED BY 'CHANGE_THIS_TO_A_STRONG_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE ON student_portal.* TO 'webapp'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

Never commit the password to GitHub.

## Step 5 — Create database tables

From the cloned repository:

```bash
cd enterprise-linux-web-database-project-v2
sudo mysql student_portal < database/database.sql
sudo mysql student_portal < database/sample-data.sql
```

Verify:

```bash
sudo mysql student_portal
```

```sql
SHOW TABLES;
SELECT * FROM students;
EXIT;
```

## Step 6 — Configure the application

Copy the example configuration:

```bash
sudo mkdir -p /etc/student-portal
sudo cp website/config.example.php /etc/student-portal/config.php
sudo vi /etc/student-portal/config.php
```

Put your real database password in that file.

Secure it:

```bash
sudo chown root:apache /etc/student-portal/config.php
sudo chmod 640 /etc/student-portal/config.php
```

## Step 7 — Deploy the website

```bash
sudo cp -r website/* /var/www/html/
```

Set ownership and permissions:

```bash
sudo chown -R root:apache /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
```

Restart:

```bash
sudo systemctl restart httpd
```

Test:

```bash
curl -I http://localhost
```

Open:

```text
http://PUBLIC_IP/
```

## Step 8 — Practice the application

Test every operation:

1. Dashboard loads.
2. Student list loads.
3. Search by name/email.
4. Filter by course.
5. Add a student.
6. Edit a student.
7. Delete a student.
8. Refresh and confirm database persistence.

## Step 9 — Health check

Open:

```text
http://PUBLIC_IP/health.php
```

The endpoint checks the application/database connection and returns a small JSON response.

Also run:

```bash
bash scripts/health_check.sh
```

## Step 10 — System monitoring

Practice:

```bash
top
ps -ef
free -h
df -h
uptime
sudo ss -lntp
```

Then:

```bash
bash scripts/system_report.sh
```

## Step 11 — Backup

Website backup:

```bash
sudo bash scripts/backup_website.sh
```

Database backup:

```bash
sudo bash scripts/backup_database.sh
```

Check:

```bash
ls -lh backups/
```

## Step 12 — Restore practice

First create a backup.

Then intentionally remove test data:

```bash
sudo mysql student_portal
```

```sql
DELETE FROM students WHERE email='backup-test@example.com';
EXIT;
```

Restore:

```bash
sudo bash scripts/restore_database.sh backups/YOUR_BACKUP_FILE.sql.gz
```

Verify:

```bash
sudo mysql student_portal -e "SELECT * FROM students;"
```

## Step 13 — Cron automation

Edit root's Cron:

```bash
sudo crontab -e
```

Example:

```cron
0 2 * * * /path/to/project/scripts/backup_database.sh >> /var/log/student-portal-backup.log 2>&1
```

Check:

```bash
sudo crontab -l
```

For practice, use a schedule a few minutes in the future and verify the backup is created.

## Step 14 — Break and troubleshoot

This is the most important DevOps practice.

### Failure A — Stop Apache

```bash
sudo systemctl stop httpd
```

Test:

```bash
curl -I http://localhost
```

Troubleshoot:

```bash
systemctl status httpd
sudo journalctl -u httpd -n 50 --no-pager
sudo ss -lntp | grep :80
```

Fix:

```bash
sudo systemctl start httpd
```

### Failure B — Wrong website permissions

```bash
sudo chmod -R 000 /var/www/html
```

Test the website.

Fix:

```bash
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
sudo chown -R root:apache /var/www/html
```

### Failure C — Database service stopped

```bash
sudo systemctl stop mariadb
```

Test:

```bash
bash scripts/health_check.sh
```

Troubleshoot:

```bash
systemctl status mariadb
sudo journalctl -u mariadb -n 50 --no-pager
sudo ss -lntp | grep 3306
```

Fix:

```bash
sudo systemctl start mariadb
```

### Failure D — Database authentication problem

Check:

```bash
sudo mysql -u webapp -p student_portal
```

Then verify grants:

```bash
sudo mysql -e "SHOW GRANTS FOR 'webapp'@'localhost';"
```

### Failure E — Website works but database doesn't

Use this order:

```text
Apache
  ↓
PHP
  ↓
MariaDB service
  ↓
3306 listener
  ↓
DB name
  ↓
DB user
  ↓
DB permissions
  ↓
PHP mysqli extension
  ↓
Apache error log
```

---

# GitHub Workflow

## 1. Configure Git

```bash
git config --global user.name "YOUR NAME"
git config --global user.email "YOUR EMAIL"
```

## 2. Initialize

```bash
git init
git branch -M main
git status
```

## 3. Commit

```bash
git add .
git commit -m "feat: build enterprise Linux web database project"
```

## 4. Create GitHub repository

Recommended name:

```text
enterprise-linux-web-database-project
```

Do not upload:

- real passwords
- `.env` files
- private keys
- database production dumps
- SSH keys

## 5. Push

```bash
git remote add origin https://github.com/YOUR-USERNAME/enterprise-linux-web-database-project.git
git push -u origin main
```

---

# Resume Positioning

Use the project only after you have actually deployed and tested it.

### Enterprise Linux Web & Database Administration — AWS EC2

- Deployed a database-driven Student Management Portal on AWS EC2 using Amazon Linux, Apache, PHP and MariaDB.
- Configured Linux services, users, groups, filesystem permissions and least-privilege database access.
- Developed Bash automation for health checks, system reporting and website/database backups.
- Automated backup operations using Cron and practiced database recovery using `mysqldump`.
- Troubleshot HTTP, database, permissions, service and networking failures using `systemctl`, `journalctl`, `ss`, `curl` and Linux logs.
- Managed the project with Git/GitHub using a secure repository structure that excludes credentials and sensitive files.

## Interview rule

Do not memorize the project description.

Be able to explain:

```text
User
 ↓
AWS Security Group
 ↓
EC2
 ↓
Apache
 ↓
PHP
 ↓
MariaDB
 ↓
Bash/Cron
 ↓
Logs/Monitoring
 ↓
Backup/Restore
```

And explain at least 5 failures you intentionally created and fixed.

## Next-level upgrades


---

