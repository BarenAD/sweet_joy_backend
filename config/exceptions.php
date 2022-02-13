<?php

return [
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
];
