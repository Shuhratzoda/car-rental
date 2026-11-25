<?php
// Нусха гиред: cp config/config.example.php config/config.php
// config.php ба Git ДОХИЛ НАМЕШАВАД
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'car_rental',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',          // дар XAMPP пароли root холӣ аст
    ],
    // true — тафсилоти хатоҳоро нишон додан (танҳо ҳангоми коркард!)
    'debug' => (getenv('APP_DEBUG') ?: '1') === '1',
];
