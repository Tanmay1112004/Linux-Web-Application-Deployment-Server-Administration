# Troubleshooting Runbook

## Website not opening

```bash
systemctl status httpd
sudo ss -lntp | grep :80
curl -I http://localhost
sudo journalctl -u httpd -n 50 --no-pager
sudo tail -n 50 /var/log/httpd/error_log
```

Then check the AWS Security Group and confirm port 80 is allowed.

## Apache is running but PHP fails

```bash
php -v
php -m | grep -i mysqli
sudo systemctl restart httpd
sudo tail -n 50 /var/log/httpd/error_log
```

## Website works but database fails

```bash
systemctl status mariadb
sudo ss -lntp | grep 3306
sudo mysql -u webapp -p student_portal
sudo mysql -e "SHOW GRANTS FOR 'webapp'@'localhost';"
php -m | grep -i mysqli
```

## Permission denied

```bash
ls -ld /var/www/html
ls -l /var/www/html
sudo chown -R root:apache /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
```

## Port 80 already used

```bash
sudo ss -lntp | grep :80
sudo lsof -i :80
```

Identify the process before stopping anything.
