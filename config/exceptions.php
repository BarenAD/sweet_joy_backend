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
];
