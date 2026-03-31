<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => getenv('APP_NAME') ?: 'Iran News Portal',
        'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost:8080',
        'timezone' => getenv('APP_TIMEZONE') ?: 'UTC',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: 'db',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'iran_news',
        'user' => getenv('DB_USER') ?: 'iran_user',
        'pass' => getenv('DB_PASSWORD') ?: 'iran_pass',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    ],
    'seo' => [
        'default_title' => 'Actualites sur la guerre en Iran',
        'default_description' => 'Analyse, chronologie et decryptage de la guerre en Iran.',
    ],
];
