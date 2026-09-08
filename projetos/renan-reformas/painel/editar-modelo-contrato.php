<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';
$modeloPadrao = file_get_contents(__DIR__ . '/contrato-modelo.md');
$inicio = strpos($modeloPadrao, '# Contrato particular');
if ($inicio !== false) $modeloPadrao = substr($modeloPadrao, $inicio);
$consulta = banco()->prepare('SELECT valor FROM configuracoes_contrato WHERE chave = ?');
$consulta->execute(['modelo_base']);
$conteudo = $consulta->fetchColumn() ?: $modeloPadrao;
$salvo = isset($_GET['salvo']);
function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#151515">
  <title>Modelo-base de contrato | Renan Reformas</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body class="contract-editor-page contract-document-page">
  <main class="contract-editor contract-document-editor">
    <header class="contract-editor-bar">
      <div>
        <p class="eyebrow">Modelo-base editável</p>
        <h1>Contrato Renan Reformas</h1>
        <p>Este texto será usado como base para as próximas minutas.</p>
      </div>
      <button type="button" class="secondary-action" onclick="window.parent.postMessage('fechar-minuta','*')">Fechar</button>
    </header>

    <?php if ($salvo): ?><p class="contract-save-feedback">Modelo-base salvo para as próximas minutas.</p><?php endif; ?>

    <section class="contract-context-card" aria-label="Campos automáticos do contrato">
      <span><small>Campos</small><strong>[nome]</strong></span>
      <span><small>Orçamento</small><strong>[código]</strong></span>
      <span><small>Obra</small><strong>[valor] · [prazo]</strong></span>
    </section>

    <form method="post" action="salvar-modelo-contrato.php" class="contract-document-form">
      <label class="contract-document-label" for="contract-content">Texto do modelo-base</label>
      <textarea id="contract-content" name="conteudo" rows="30" required><?= esc($conteudo) ?></textarea>
      <p class="contract-editor-note">Mantenha os campos entre colchetes para o painel preencher cada contrato automaticamente.</p>
      <div class="contract-editor-actions">
        <button class="primary-action" type="submit">Salvar modelo-base</button>
      </div>
    </form>
  </main>
</body>
</html>

