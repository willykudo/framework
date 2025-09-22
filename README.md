clone repo 
git clone https://github.com/willykudo/framework.git for HTTPS
git clone git@github.com:willykudo/framework.git for SSH


install dependency
composer install

copy file .env.example to .env
copy .env.example .env

make migrate
php willy.php migrate

make seeder 
php willy.php seed

run server
php willy.php serve

requirement:
php 8.3.21
mysql 8.0
laragon 6.0

