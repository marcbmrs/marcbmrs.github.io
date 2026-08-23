<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/database.php';

require_login();

/**
 * Esta página é a porta de entrada do painel. Nas próximas etapas ela passará
 * a exigir login e exibirá os dados reais de propostas.
 */
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#151515">
  <title>Painel de orçamentos | Renan Reformas</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body>
  <main class="shell">
    <a class="brand" href="../" aria-label="Voltar ao site da Renan Reformas">
      <img src="../assets/icons/logo-renan-reformas.svg" width="560" height="128" alt="Renan Reformas">
    </a>

    <section class="welcome" aria-labelledby="titulo-painel">
      <p class="eyebrow">Painel de orçamentos</p>
      <h1 id="titulo-painel">Painel protegido e conectado.</h1>
      <p>O acesso agora depende de login. Nas próximas etapas, esta área concentrará clientes, propostas e PDFs.</p>
      <p class="database-status"><span aria-hidden="true"></span>Sessão autenticada e banco local disponível.</p>
      <form class="logout-form" method="post" action="logout.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <button type="submit">Sair do painel</button>
      </form>
    </section>

    <section class="next-steps" aria-label="Próximas etapas do sistema">
      <article>
        <span>01</span>
        <h2>Acesso protegido</h2>
        <p>Login individual para o Renan, com sessão segura.</p>
      </article>
      <article>
        <span>02</span>
        <h2>Clientes e propostas</h2>
        <p>Cadastro, valores, itens, prazos e condições.</p>
      </article>
      <article>
        <span>03</span>
        <h2>PDF profissional</h2>
        <p>Versão pronta para enviar pelo WhatsApp.</p>
      </article>
    </section>
  </main>
</body>
</html>
