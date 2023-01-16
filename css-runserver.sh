#!/bin/bash

cd project/
npm install
npm run build
php artisan serve --port 8888
