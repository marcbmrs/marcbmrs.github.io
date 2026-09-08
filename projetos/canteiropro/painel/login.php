<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
iniciarSessaoPainel();
cabecalhosSeguranca();

if (equipeAutenticada()) {
    header('Location: painel.php');
    exit;
}

$erro = isset($_GET['erro']);
$saiu = isset($_GET['saiu']);
$seguranca = isset($_GET['seguranca']);
$voltar = (string) ($_GET['voltar'] ?? 'painel.php');
if (!preg_match('/^[a-z-]+\.php$/', $voltar)) $voltar = 'painel.php';
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#151515"><title>Entrar | CanteiroPro</title><link rel="stylesheet" href="styles/main.css"></head>
<body class="login-page"><main class="login-shell">
  <header class="login-brand"><img src="assets/images/logo-canteiropro.svg" width="142" height="48" alt="CanteiroPro"></header>
  <section class="login-intro"><p class="eyebrow">Área da equipe</p><h1>Vamos trabalhar?</h1><p>Entre para organizar orçamentos, obras e contratos.</p></section>
  <section class="login-card" aria-labelledby="login-title"><div class="login-card-heading"><p class="eyebrow">Acesso seguro</p><h2 id="login-title">Acessar painel</h2><p>Use seu e-mail e sua senha.</p></div>
    <?php if ($erro): ?><p class="login-feedback is-error" role="alert">E-mail ou senha incorretos. Tente novamente.</p><?php endif; ?>
    <?php if ($seguranca): ?><p class="login-feedback is-error" role="alert">Sua sessão foi atualizada. Informe os dados novamente para entrar.</p><?php endif; ?>
    <?php if ($saiu): ?><p class="login-feedback">Você saiu do painel com segurança.</p><?php endif; ?>
    <form method="post" action="autenticar.php"><?= campoCsrf() ?><input type="hidden" name="voltar" value="<?= htmlspecialchars($voltar, ENT_QUOTES, 'UTF-8') ?>"><label>E-mail<input type="email" name="email" inputmode="email" autocomplete="email" placeholder="voce@empresa.com" required autofocus></label><label>Senha<input type="password" name="senha" autocomplete="current-password" placeholder="Sua senha" required></label><label class="remember-check"><input type="checkbox" name="lembrar" value="1"><span>Lembrar de mim neste dispositivo</span></label><button class="primary-action" type="submit">Entrar no painel <span aria-hidden="true">→</span></button></form>
  </section>
  <p class="login-help">Acesso exclusivo da equipe do prestador.</p>
</main></body></html>

