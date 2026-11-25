<?php
// Ин файл дар .gitignore аст. Қиматҳои воқеии худро ин ҷо гузоред.
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'car_rental',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    'debug' => (getenv('APP_DEBUG') ?: '1') === '1',
];
