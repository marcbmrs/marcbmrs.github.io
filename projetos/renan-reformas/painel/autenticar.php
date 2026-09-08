<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
cabecalhosSeguranca();
if (!csrfValido()) { header('Location: login.php?seguranca=1'); exit; }
$email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
$senha = (string) ($_POST['senha'] ?? '');
$voltar = (string) ($_POST['voltar'] ?? 'painel.php');
if (!preg_match('/^[a-z-]+\.php$/', $voltar)) $voltar = 'painel.php';

$consulta = banco()->prepare('SELECT id, nome, email, senha_hash FROM usuarios_equipe WHERE email = ? AND ativo = 1 LIMIT 1');
$consulta->execute([$email]);
$usuario = $consulta->fetch();
if (loginBloqueado($email) || !$usuario || !password_verify($senha, $usuario['senha_hash'])) {
    registrarFalhaLogin($email);
    header('Location: login.php?erro=1&voltar=' . rawurlencode($voltar));
    exit;
}
limparFalhasLogin($email);
iniciarSessaoPainel();
session_regenerate_id(true);
$_SESSION['equipe_usuario'] = ['id' => (int) $usuario['id'], 'nome' => $usuario['nome'], 'email' => $usuario['email']];
banco()->prepare('UPDATE usuarios_equipe SET ultimo_acesso_em = NOW() WHERE id = ?')->execute([$usuario['id']]);
if (!empty($_POST['lembrar'])) criarLembreteEquipe((int) $usuario['id']);
header('Location: ' . $voltar);
