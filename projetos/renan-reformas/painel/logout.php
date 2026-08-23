<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !valid_csrf_token($_POST['csrf_token'] ?? null)) {
    http_response_code(405);
    exit;
}

logout_user();
header('Location: login.php');
exit;
