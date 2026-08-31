# Architecture

## Single server

Browser -> AWS Security Group -> EC2 -> Apache -> PHP -> MariaDB

Operational layer:
Linux users/groups -> permissions -> systemd -> logs -> Bash -> Cron -> backups -> monitoring

## Advanced

Browser -> Web EC2 -> private TCP 3306 -> DB EC2

Security:
- SSH restricted to administrator IP
- HTTP/HTTPS public
- DB 3306 restricted to Web Server Security Group
