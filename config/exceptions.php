<?php

return [
    'test' => [
        'id' => -1,
        'message' => 'test_exception',
        'http_code' => 100
    ],
    'unauthorized' => [
        'id' => 0,
        'message' => 'Пользователь не авторизован',
        'http_code' => 401
    ],
    'is_not_admin' => [
        'id' => 1,
        'message' => 'Авторизованный пользователь не является администратором',
        'http_code' => 403
    ],
    'file_is_not_stored' => [
        'id' => 2,
        'message' => 'Неудалось сохранить файл',
        'http_code' => 500
    ],
    'file_is_not_destroy' => [
        'id' => 3,
        'message' => 'Неудалось удалить файл',
        'http_code' => 500
    ],
    'file_is_not_update' => [
        'id' => 4,
        'message' => 'Неудалось обновить файл',
        'http_code' => 500
    ],
    'product_already_in_shop' => [
        'id' => 5,
        'message' => 'Продукт уже добавлен в данный магазин. Используйте метод редактирования.',
        'http_code' => 409
    ],
    'not_enough_permissions' => [
        'id' => 6,
        'message' => 'Недостаточно прав администрирования.',
        'http_code' => 403
    ],
    'invalid_login' => [
        'id' => 7,
        'message' => 'Неверный логин или пароль',
        'http_code' => 401
    ],
    'user_already_exists' => [
        'id' => 8,
        'message' => 'Пользователь с таким e-mail или телефоном уже существует.',
        'http_code' => 400
    ],
];
