<?php
declare(strict_types=1);

if (!function_exists('banco')) {
function banco(): PDO
    {
        static $pdo;
        if ($pdo instanceof PDO) return $pdo;
        $config = require __DIR__ . '/config.local.php';
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
            $config['username'], $config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        return $pdo;
    }
}

function configuracaoPainel(): array
{
    return require __DIR__ . '/config.local.php';
}

function diretorioFotosObras(): string
{
    $config = configuracaoPainel();
    return rtrim((string) ($config['diretorio_fotos_obras'] ?? 'C:/MarcosDev-Originais/renan-reformas/images/obras'), '/\\');
}
