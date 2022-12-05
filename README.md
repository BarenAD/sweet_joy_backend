<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Требования к окружению

- Composer
- PHP >= 7.4
- PDO (расширение для PHP)
- GD (расширение для PHP (также включить в php.ini))
- MCrypt (расширение для PHP)
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
- Сконфигурировать Laravel-passport `php artisan passport:install`
- Заполнить базу `php artisan db:seed`
- Установить, запустить и добавить в автозагрузку redis-server
- Готово. Можно запускать проект `php artisan serve`
