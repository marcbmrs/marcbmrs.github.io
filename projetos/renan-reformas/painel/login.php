<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/database.php';

if (is_authenticated()) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $token = $_POST['csrf_token'] ?? null;

    if (!valid_csrf_token($token) || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'E-mail ou senha inválidos.';
    } else {
        $statement = database()->prepare(
            'SELECT id, password_hash FROM users WHERE email = :email LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        if (is_array($user) && password_verify($password, $user['password_hash'])) {
            login_user((int) $user['id']);
            header('Location: index.php');
            exit;
        }

        $error = 'E-mail ou senha inválidos.';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#151515">
  <title>Acessar painel | Renan Reformas</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body>
  <main class="shell login-shell">
    <a class="brand" href="../" aria-label="Voltar ao site da Renan Reformas">
      <img src="../assets/icons/logo-renan-reformas.svg" width="560" height="128" alt="Renan Reformas">
    </a>

    <section class="login-card" aria-labelledby="titulo-login">
      <p class="eyebrow">Área restrita</p>
      <h1 id="titulo-login">Acessar painel</h1>
      <p>Use o e-mail e a senha cadastrados para gerenciar propostas.</p>

      <?php if ($error !== null): ?>
        <p class="form-error" role="alert"><?= e($error) ?></p>
      <?php endif; ?>

      <form method="post" class="login-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>
          E-mail
          <input type="email" name="email" autocomplete="email" required>
        </label>
        <label>
          Senha
          <input type="password" name="password" autocomplete="current-password" required>
        </label>
        <button type="submit">Entrar</button>
      </form>
    </section>
  </main>
</body>
</html>
