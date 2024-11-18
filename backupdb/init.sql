ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'write_real_password';
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'write_real_password';
FLUSH PRIVILEGES;
SELECT user, host, plugin FROM mysql.user WHERE user = 'root';