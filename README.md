<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Требования к окружению

- Composer
- PHP >= 5.6.4
- PDO расширение для PHP (для версии 5.1+)
- GD расширение для PHP (также включить в php.ini)
- MCrypt расширение для PHP (для версии 5.0)
- OpenSSL (расширение для PHP)
- Mbstring (расширение для PHP)
- Tokenizer (расширение для PHP)
- XML (расширение для PHP) (для версии 5.3+)
- redis (расширение для PHP)
- redis-server (установить, запустить и добавить в автозагрузку)

## Инструкция по разворачиванию

- Убедиться, что все требования к окружению удовлетворены
- Склонировать проект `git clone {URL}`
- Создать базу данных и пользователя в СУБД
- Скопировать конфигурационный файл `cp .env.example .env`
- Произвести настройку файла `.env`
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD
    - (опционально) DB_CONNECTION
- Установить все зависимости `composer install`
- Сгенерировать ключ приложения `php artisan key:generate`
- Выполнить `composer dump-autoload`
- Создать ссылку для хранилища `php artisan storage:link`
- Накатить миграции `php artisan migrate`
- Заполнить базу `php artisan db:seed`
- Установить, запустить и добавить в автозагрузку redis-server
- Готово. Можно запускать проект `php artisan serve`

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
