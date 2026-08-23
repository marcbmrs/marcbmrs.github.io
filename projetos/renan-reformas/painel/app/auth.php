<?php
declare(strict_types=1);

// Este arquivo é uma biblioteca interna, nunca uma página acessada diretamente.
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(404);
    exit;
}

/**
 * Informa se a sessão atual pertence a um usuário autenticado.
 */
function is_authenticated(): bool
{
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

/**
 * Devolve o ID do usuário autenticado ou null para visitantes.
 */
function current_user_id(): ?int
{
    return is_authenticated() ? $_SESSION['user_id'] : null;
}

/**
 * Registra o usuário na sessão depois que a senha foi validada pelo banco.
 */
function login_user(int $userId): void
{
    session_regenerate_id(true);

    $_SESSION['user_id'] = $userId;
    $_SESSION['logged_in_at'] = time();
}

/**
 * Bloqueia uma página interna quando não existe um usuário autenticado.
 */
function require_login(): void
{
    if (is_authenticated()) {
        return;
    }

    header('Location: login.php');
    exit;
}

/**
 * Encerra a sessão atual e remove o cookie do navegador.
 */
function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parameters = session_get_cookie_params();

        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $parameters['path'],
            'domain' => $parameters['domain'],
            'secure' => $parameters['secure'],
            'httponly' => $parameters['httponly'],
            'samesite' => $parameters['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}
