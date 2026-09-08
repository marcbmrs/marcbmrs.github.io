<?php
declare(strict_types=1);

function iniciarSessaoPainel(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        session_name('canteiropro_painel');
        session_start([
            'cookie_lifetime' => 0,
            'cookie_path' => '/',
            'cookie_httponly' => true,
            'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function cabecalhosSeguranca(): void
{
    if (headers_sent()) return;
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: same-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header('X-Frame-Options: SAMEORIGIN');
    header("Content-Security-Policy: default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; img-src 'self' data: blob:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'");
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

function tokenCsrf(): string
{
    iniciarSessaoPainel();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function campoCsrf(): string
{
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(tokenCsrf(), ENT_QUOTES, 'UTF-8') . '">';
}

function csrfValido(): bool
{
    iniciarSessaoPainel();
    $recebido = (string) ($_POST['csrf'] ?? '');
    return $recebido && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $recebido);
}

function exigirCsrf(): void
{
    if (!csrfValido()) {
        http_response_code(403);
        exit('Não foi possível validar esta ação. Volte ao painel e tente novamente.');
    }
}

function chaveTentativaLogin(string $email): array
{
    return [hash('sha256', $email), hash('sha256', (string) ($_SERVER['REMOTE_ADDR'] ?? ''))];
}

function loginBloqueado(string $email): bool
{
    require_once __DIR__ . '/db.php';
    $consulta = banco()->prepare('SELECT 1 FROM tentativas_login WHERE email_hash = ? AND ip_hash = ? AND bloqueado_ate > NOW() LIMIT 1');
    $consulta->execute(chaveTentativaLogin($email));
    return (bool) $consulta->fetchColumn();
}

function registrarFalhaLogin(string $email): void
{
    require_once __DIR__ . '/db.php';
    banco()->prepare('INSERT INTO tentativas_login (email_hash, ip_hash, tentativas, primeira_tentativa_em, bloqueado_ate) VALUES (?, ?, 1, NOW(), NULL) ON DUPLICATE KEY UPDATE tentativas = IF(primeira_tentativa_em < DATE_SUB(NOW(), INTERVAL 15 MINUTE), 1, tentativas + 1), primeira_tentativa_em = IF(primeira_tentativa_em < DATE_SUB(NOW(), INTERVAL 15 MINUTE), NOW(), primeira_tentativa_em), bloqueado_ate = IF(tentativas + 1 >= 5, DATE_ADD(NOW(), INTERVAL 15 MINUTE), bloqueado_ate)')->execute(chaveTentativaLogin($email));
}

function limparFalhasLogin(string $email): void
{
    require_once __DIR__ . '/db.php';
    banco()->prepare('DELETE FROM tentativas_login WHERE email_hash = ? AND ip_hash = ?')->execute(chaveTentativaLogin($email));
}

function cookieSegura(string $nome, string $valor, int $expiraEm): void
{
    setcookie($nome, $valor, [
        'expires' => $expiraEm,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function tentarLembrarEquipe(): void
{
    if (isset($_SESSION['equipe_usuario']) || empty($_COOKIE['canteiropro_lembrete'])) return;
    $partes = explode(':', (string) $_COOKIE['canteiropro_lembrete'], 2);
    if (count($partes) !== 2 || !preg_match('/^[a-f0-9]{24}$/', $partes[0]) || !preg_match('/^[a-f0-9]{64}$/', $partes[1])) return;
    require_once __DIR__ . '/db.php';
    $consulta = banco()->prepare('SELECT s.usuario_id, s.token_hash, u.nome, u.email FROM sessoes_lembradas s INNER JOIN usuarios_equipe u ON u.id = s.usuario_id WHERE s.seletor = ? AND s.expira_em > NOW() AND u.ativo = 1 LIMIT 1');
    $consulta->execute([$partes[0]]);
    $sessao = $consulta->fetch();
    if (!$sessao || !hash_equals($sessao['token_hash'], hash('sha256', $partes[1]))) return;
    session_regenerate_id(true);
    $_SESSION['equipe_usuario'] = ['id' => (int) $sessao['usuario_id'], 'nome' => $sessao['nome'], 'email' => $sessao['email']];
}

function equipeAutenticada(): bool
{
    iniciarSessaoPainel();
    if (!empty($_SESSION['ultima_atividade']) && time() - (int) $_SESSION['ultima_atividade'] > 1800) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    tentarLembrarEquipe();
    if (isset($_SESSION['equipe_usuario'])) $_SESSION['ultima_atividade'] = time();
    return isset($_SESSION['equipe_usuario']['id']);
}

function criarLembreteEquipe(int $usuarioId): void
{
    require_once __DIR__ . '/db.php';
    $seletor = bin2hex(random_bytes(12));
    $verificador = bin2hex(random_bytes(32));
    $expiraEm = time() + 60 * 60 * 24 * 30;
    banco()->prepare('DELETE FROM sessoes_lembradas WHERE usuario_id = ? OR expira_em <= NOW()')->execute([$usuarioId]);
    banco()->prepare('INSERT INTO sessoes_lembradas (seletor, usuario_id, token_hash, expira_em) VALUES (?, ?, ?, FROM_UNIXTIME(?))')->execute([$seletor, $usuarioId, hash('sha256', $verificador), $expiraEm]);
    cookieSegura('canteiropro_lembrete', $seletor . ':' . $verificador, $expiraEm);
}

function apagarLembreteEquipe(): void
{
    if (!empty($_COOKIE['canteiropro_lembrete'])) {
        $seletor = explode(':', (string) $_COOKIE['canteiropro_lembrete'], 2)[0];
        if (preg_match('/^[a-f0-9]{24}$/', $seletor)) {
            require_once __DIR__ . '/db.php';
            banco()->prepare('DELETE FROM sessoes_lembradas WHERE seletor = ?')->execute([$seletor]);
        }
    }
    cookieSegura('canteiropro_lembrete', '', time() - 3600);
}

function exigirEquipe(): void
{
    cabecalhosSeguranca();
    if (equipeAutenticada()) return;
    $destino = basename((string) ($_SERVER['PHP_SELF'] ?? 'painel.php'));
    header('Location: login.php?voltar=' . rawurlencode($destino));
    exit;
}

function usuarioEquipe(): array
{
    return $_SESSION['equipe_usuario'] ?? [];
}

