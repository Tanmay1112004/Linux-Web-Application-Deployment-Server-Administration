# Deployment Notes

## Manual learning path

1. Launch Amazon Linux 2023 EC2.
2. Configure Security Group for SSH 22 from your IP and HTTP 80 from the internet.
3. Install Apache, PHP, MariaDB and Git.
4. Start/enable services.
5. Secure MariaDB.
6. Create `student_portal`.
7. Create `webapp` with least privilege.
8. Import schema and sample data.
9. Put real credentials in `/etc/student-portal/config.php`.
10. Deploy `website/` to `/var/www/html`.
11. Test browser + curl.
12. Run health check.
13. Test backups.
14. Configure Cron.
15. Intentionally break services and troubleshoot.

## Security rules

- Never commit passwords.
- Never commit `.pem` files.
- Never expose 3306 publicly.
- Restrict SSH to your administrator IP.
- Keep database configuration outside `/var/www/html`.
- Use a non-root application database user.
