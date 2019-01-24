<?php
require_once __DIR__ . '/vendor/autoload.php';

try {
    $mcrate = new \mcrate\bonus\McRate('my_amazing_secret', \mcrate\bonus\McRate::TYPE_GET, $_GET);
} catch (Exception $e) {
    // Данных в _GET не достаточно.
    http_response_code(400);
    exit(1);
}

if ($mcrate->validate()) {
    $data = $mcrate->getData();

    // Валидация успешна.
    // Совешайте любые манипуляции для выпдачи бонуса игроку. Данные в удобном для IDE формате описаны в $data объекте.
} else {
    // Валидация не прошла.
    http_response_code(400);
    exit(1);
}
