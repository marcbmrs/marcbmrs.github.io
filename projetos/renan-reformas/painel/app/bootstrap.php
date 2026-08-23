<?php
declare(strict_types=1);

// Este arquivo só pode ser carregado por outra página PHP; não é uma rota pública.
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(404);
    exit;
}

// Evita que o PHP aceite identificadores de sessão criados por terceiros.
ini_set('session.use_strict_mode', '1');

session_set_cookie_params([
    'httponly' => true,
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'samesite' => 'Lax',
]);

session_start();

// Cabeçalhos básicos para reduzir superfícies de ataque no painel.
header('Content-Security-Policy: default-src \'self\'; style-src \'self\'; img-src \'self\'; base-uri \'self\'; frame-ancestors \'none\'; form-action \'self\'');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

/**
 * Escapa qualquer texto que venha de usuário antes de inseri-lo em HTML.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Cria ou devolve o token que impede envios de formulário por outros sites.
 */
function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Confere se o formulário foi realmente enviado a partir deste painel.
 */
function valid_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && is_string($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
