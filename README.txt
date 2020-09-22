How to use this:
1/
composer install
2/
npm install
npm run dev
3/
cp .env.example .env
=>edit .env and pay attention to database connection and fill telegram-bot token
4/
docker-compose up -d
5/
connect to container with php-fpm
php artisan migrate:fresh --seed
6/
send to bot message
and if this message contains only digits, bot search order and reply you with answer
7/
for check messages to bot and generate answer, run
php artisan bot:updates

Addetional you can check logs in database

=============================================================

Some info, how this project was created...


1. Install Project
install laravel
install cool faker
composer require mbezhanov/faker-provider-collection


standart faker help
https://github.com/fzaninotto/Faker

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
2. copy files docker-compose.yaml and folder .docker
3. npm install
   npm run dev

4. check site (localhost)

5. php artisan make:model Order -m
   edit database/migrations/..orders_table

6. Make tables in database
php artisan migrate

7. make Factories
php artisan make:factory OrderFactory

8. seed tables in tinker
php artisan tinker
$users = User::factory()->count(10)->create();
$orders = Order::factory()->count(50)->create();

Edit database\seeders\DatabaseSeeder and add all tables

9. Make auto-seeders
php artisan make:seeder UserSeeder
php artisan make:seeder OrderSeeder

Seed separated tables
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=OrderSeeder

Entire database we can make with
php artisan migrate:fresh --seed

#####################################################
#####################################################
Get Token from Bot-father

make tables for storing telegram users and chats.

php artisan make:model TelegramUser -m
php artisan make:model TelegramChat -m

#####################################################
#####################################################
create console comand
php artisan make:command BotUpdates --command=bot:updates

edit file
\app\Console\Commands\BotUpdates.php

To get messages from telegram, run
php artisan bot:updates

This command you can add to crone and run one time per minute
