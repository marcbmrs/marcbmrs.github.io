<?php
declare(strict_types=1);

// Biblioteca interna: conexões devem passar por database().
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(404);
    exit;
}

/**
 * Cria uma única conexão PDO por requisição.
 */
function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $configPath = __DIR__ . '/config.local.php';

    if (!is_file($configPath)) {
        throw new RuntimeException('A configuração local do banco não foi encontrada.');
    }

    /** @var array{host: string, database: string, username: string, password: string} $config */
    $config = require $configPath;
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";

    $connection = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $connection;
}
