<?php
$envFile = __DIR__ . '/env';
// Проверяем, существует ли файл .env
if (file_exists($envFile)) {
    // Загружаем содержимое файла .env
    $envContent = file_get_contents($envFile);

    // Разбиваем содержимое файла на строки
    $lines = explode("\n", $envContent);

    // Обработка каждой строки
    foreach ($lines as $line) {
        // Разбиваем строку на ключ и значение
        list($key, $value) = explode('=', $line, 2);

        // Убираем лишние пробелы и кавычки
        $key = trim($key);
        $value = trim($value);

        // Устанавливаем переменные окружения
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
} else {
    // Файл .env не найден
    die('.env file not found.');
}

function encryptPassword($password, $key) {
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptPassword($encryptedPassword, $key) {
    list($encryptedPassword, $iv) = explode('::', base64_decode($encryptedPassword), 2);
    return openssl_decrypt($encryptedPassword, 'aes-256-cbc', $key, 0, $iv);
}

// Получаем значение encryption_key
$encryption_key = getenv('encryption_key');

?>