# Mysql数据库创建参考
create database homestead character set utf8;
GRANT ALL PRIVILEGES ON homestead.* TO 'homestead'@'localhost' IDENTIFIED BY 'secret' WITH GRANT OPTION;
flush privileges;

# 相关命令
composer install
npm install
npm run dev
npm run build

# 补充中...

