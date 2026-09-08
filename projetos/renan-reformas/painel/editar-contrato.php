<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';
require __DIR__ . '/app/minuta-contrato.php';
$id = (int) ($_GET['id'] ?? 0);
$consulta = banco()->prepare('SELECT o.*, c.nome, c.telefone FROM orcamentos o JOIN clientes c ON c.id = o.cliente_id WHERE o.id = ? AND o.status = "aprovado"');
$consulta->execute([$id]);
$orcamento = $consulta->fetch();
if (!$orcamento) { http_response_code(404); exit('Orçamento aprovado não encontrado.'); }
$minuta = banco()->prepare('SELECT conteudo FROM minutas_contrato WHERE orcamento_id = ?');
$minuta->execute([$id]);
$conteudo = $minuta->fetchColumn() ?: montarMinutaContrato($orcamento);
$salvo = isset($_GET['salvo']);
$obra = banco()->prepare('SELECT id, status, atualizado_em FROM obras_acompanhamento WHERE orcamento_id = ? LIMIT 1');
$obra->execute([$id]);
$obraVinculada = $obra->fetch() ?: null;
function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#151515">
  <title>Minuta de contrato | Renan Reformas</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body class="contract-editor-page contract-document-page">
  <main class="contract-editor contract-document-editor">
    <header class="contract-editor-bar">
      <div>
        <p class="eyebrow">Minuta editável</p>
        <h1><?= esc($orcamento['nome']) ?></h1>
        <p><?= esc($orcamento['codigo']) ?> · Dados preenchidos a partir do orçamento aprovado.</p>
      </div>
      <button type="button" class="secondary-action" onclick="window.parent.postMessage('fechar-minuta','*')">Fechar</button>
    </header>

    <?php if ($salvo): ?>
      <section class="contract-next-card" aria-label="Próximo passo do contrato">
        <div>
          <p class="eyebrow">Minuta salva</p>
          <h2><?= $obraVinculada ? 'Obra já criada' : 'Próximo passo: abrir a obra' ?></h2>
          <p><?= $obraVinculada ? 'A formalização já está conectada à execução. Agora registre a próxima atualização para o cliente.' : 'Com a minuta conferida, crie a obra e registre a primeira atualização.' ?></p>
        </div>
        <div class="contract-next-actions">
          <?php if ($obraVinculada): ?>
            <a class="primary-action" href="atualizar-obra.php?id=<?= (int) $obraVinculada['id'] ?>" target="_top">Atualizar obra</a>
            <a class="secondary-action" href="orcamento.php?id=<?= $id ?>" target="_top">Voltar ao orçamento</a>
          <?php else: ?>
            <form method="post" action="criar-obra.php" target="_top">
              <input type="hidden" name="orcamento_id" value="<?= $id ?>">
              <button class="primary-action" type="submit">Criar obra agora</button>
            </form>
            <a class="secondary-action" href="orcamento.php?id=<?= $id ?>" target="_top">Voltar ao orçamento</a>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>

    <section class="contract-context-card" aria-label="Resumo da minuta">
      <span><small>Cliente</small><strong><?= esc($orcamento['nome']) ?></strong></span>
      <span><small>Orçamento</small><strong><?= esc($orcamento['codigo']) ?></strong></span>
      <span><small>Valor</small><strong>R$ <?= number_format((int) $orcamento['total_centavos'] / 100, 2, ',', '.') ?></strong></span>
    </section>

    <form method="post" action="salvar-minuta-contrato.php" class="contract-document-form">
      <input type="hidden" name="orcamento_id" value="<?= $id ?>">
      <label class="contract-document-label" for="contract-content">Texto do contrato</label>
      <textarea id="contract-content" name="conteudo" rows="30" required><?= esc($conteudo) ?></textarea>
      <p class="contract-editor-note">Rascunho operacional. A minuta deve passar por revisão jurídica antes de uso contratual.</p>
      <div class="contract-editor-actions">
        <button class="primary-action" type="submit">Salvar minuta</button>
      </div>
    </form>
  </main>
</body>
</html>

