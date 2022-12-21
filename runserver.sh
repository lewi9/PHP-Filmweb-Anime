#!/bin/bash
# this script should be run after executing 'source php.env' command in main project directory and running development container ('run dev' command)
cd project/
docker run --name=mysql --net=host --rm --env MYSQL_ROOT_PASSWORD=root123 --env MYSQL_ROOT_HOST=% --env MYSQL_DATABASE=test --env MYSQL_USER=test --env MYSQL_PASSWORD=test123 -d mysql/mysql-server:8.0
 while ! timeout 1 bash -c "echo > /dev/tcp/localhost/3306" 2> /dev/null; do sleep 1; done; echo "Done.";
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
mysqldump -h127.0.0.1 -u root --password=root123 test > tests_codeception/_data/dump.sql
npm install
npm run build
php artisan serve --port 8888
