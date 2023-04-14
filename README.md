<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Installation

Follows the below step to setup project:

- Please add database details & Tinify Key for image compression in .env file.
- Run **composer update** command for Laravel packages install.
- After successfully **composer update** remove **post-update-cmd** array of commands from **composer.json** file.
- Run **php artisan migrate** command for database migration. If you need dummy data in database run **php artisan migrate --seed** command instead of **php artisan migrate** command.
- Run **php artisan passport:install** command for Laravel Passport encryption keys generation.
- Run **npm install** command for NPM packages install.
- Run **npm run dev** command for complied VueJS code.
- [*Optional*] Add mailing credentials in .env for Forgot Password and Any email send functionality.
- [*Optional*] Add pusher credentials in .env for Broadcasting functionality.
- Run **php artisan serve** to run project.
- Safe side run laravel caching commands If page not load.
