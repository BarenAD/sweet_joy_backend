<?php


namespace App\Http\services;


class GeneratedAborting
{
    public static function notFound() {
        abort(404, 'Ничего не найдено');
    }

    public static function accessDeniedGrandsAdmin() {
        abort(403, 'Недостаточно прав администрирования.');
    }

    public static function adminAlreadyExist() {
        abort(409, 'Данный администратор уже существует. Используйте PUT метод.');
    }

    public static function youAreNotAdmin() {
        abort(403, 'Вы не являетесь администратором.');
    }
}
