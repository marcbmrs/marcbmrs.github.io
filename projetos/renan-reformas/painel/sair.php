<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
iniciarSessaoPainel();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: painel.php'); exit; }
exigirCsrf();
apagarLembreteEquipe();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
}
session_destroy();
header('Location: login.php?saiu=1');
