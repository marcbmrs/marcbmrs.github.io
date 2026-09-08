<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$id = (int) ($_GET['id'] ?? 0);
$consulta = banco()->prepare('SELECT * FROM obras_acompanhamento WHERE id = ?');
$consulta->execute([$id]);
$obra = $consulta->fetch();
if (!$obra) { http_response_code(404); exit('Obra não encontrada.'); }

function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
function dataCurta(?string $data): string { return $data ? date('d/m/Y', strtotime($data)) : 'Sem data'; }
function statusObra(string $status): string { return ['em_andamento' => 'Em andamento', 'pausada' => 'Pausada', 'concluida' => 'Concluída'][$status] ?? 'Em andamento'; }

$atualizacaoId = (int) ($_GET['editar'] ?? 0);
$edicao = null;
$fotosEdicao = [];
if ($atualizacaoId) {
    $consulta = banco()->prepare('SELECT * FROM atualizacoes_obra WHERE id = ? AND obra_id = ?');
    $consulta->execute([$atualizacaoId, $id]);
    $edicao = $consulta->fetch();
    if (!$edicao) { http_response_code(404); exit('Atualização não encontrada.'); }
    $fotosEdicao = banco()->prepare('SELECT * FROM fotos_atualizacao_obra WHERE atualizacao_id = ? ORDER BY id');
    $fotosEdicao->execute([$atualizacaoId]);
    $fotosEdicao = $fotosEdicao->fetchAll();
}
$historico = banco()->prepare('SELECT * FROM atualizacoes_obra WHERE obra_id = ? ORDER BY data_atualizacao DESC, id DESC');
$historico->execute([$id]);
$historico = $historico->fetchAll();
$statusSelecionado = $edicao['status'] ?? $obra['status'] ?? 'em_andamento';
$resumoAtual = (string) ($edicao['resumo'] ?? '');
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#151515">
  <title>Atualizar obra | CanteiroPro</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body class="update-app-page"><main class="app-shell update-page update-app-shell field-update-shell">
  <header class="topbar"><a class="brand" href="painel.php"><span class="brand-mark" aria-hidden="true"></span><span>CANTEIRO <b>PRO</b></span></a><a class="quote-back" href="obras.php">Voltar</a></header>

  <section class="field-update-hero">
    <p class="eyebrow"><?= $edicao ? 'Editar atualização' : 'Nova atualização' ?></p>
    <h1><?= esc($obra['titulo']) ?></h1>
    <div class="field-work-state">
      <span><small>Status atual</small><strong><?= esc(statusObra((string) $obra['status'])) ?></strong></span>
      <span><small>Próxima etapa</small><strong><?= esc($obra['proxima_etapa'] ?: 'A confirmar') ?></strong></span>
    </div>
  </section>

  <?php if (!empty($_GET['erro'])): ?><p class="form-error" role="alert"><?= esc((string) $_GET['erro']) ?></p><?php endif; ?>

  <form class="update-form field-update-form" action="salvar-atualizacao-obra.php" method="post" enctype="multipart/form-data" data-update-form>
    <input type="hidden" name="obra_id" value="<?= (int) $obra['id'] ?>">
    <?php if ($edicao): ?><input type="hidden" name="atualizacao_id" value="<?= (int) $edicao['id'] ?>"><?php endif; ?>

    <section class="client-card field-update-card">
      <div class="section-title"><span class="step">1</span><div><h2>Andamento</h2><p>Atualize o cliente com o que mudou na obra.</p></div></div>
      <div class="fields">
        <label>Data<input name="data_atualizacao" type="date" value="<?= esc($edicao['data_atualizacao'] ?? date('Y-m-d')) ?>" required></label>
        <fieldset class="status-choice wide-field">
          <legend>Status da obra</legend>
          <label><input type="radio" name="status" value="em_andamento" <?= $statusSelecionado === 'em_andamento' ? 'checked' : '' ?>><span>Em andamento</span></label>
          <label><input type="radio" name="status" value="pausada" <?= $statusSelecionado === 'pausada' ? 'checked' : '' ?>><span>Pausada</span></label>
          <label><input type="radio" name="status" value="concluida" <?= $statusSelecionado === 'concluida' ? 'checked' : '' ?>><span>Concluída</span></label>
        </fieldset>
        <label class="wide-field summary-field">O que foi feito hoje<textarea name="resumo" rows="4" maxlength="500" placeholder="Ex.: Concluímos a impermeabilização e iniciamos o assentamento do revestimento." required data-summary-field><?= esc($resumoAtual) ?></textarea><small><span data-summary-count><?= strlen($resumoAtual) ?></span>/500 caracteres · escreva como uma mensagem para o cliente.</small></label>
        <label class="wide-field">Próximo passo <em class="is-optional">Opcional</em><input name="proximo_passo" type="text" maxlength="255" value="<?= esc($edicao['proximo_passo'] ?? '') ?>" placeholder="Ex.: Finalizar o revestimento da parede principal."><small>Se preenchido, vira a próxima etapa visível para o cliente.</small></label>
      </div>
    </section>

    <section class="client-card field-update-card photo-update-card">
      <div class="section-title"><span class="step">2</span><div><h2>Fotos</h2><p>Adicione registros de antes, durante ou depois.</p></div></div>
      <div class="fields">
        <label>Etapa das fotos<select name="etapa_fotos"><option value="durante">Durante a obra</option><option value="antes">Antes da obra</option><option value="depois">Depois da obra</option></select></label>
        <label class="wide-field photo-input photo-dropzone">Adicionar fotos<input name="fotos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple><small>Até cinco fotos por atualização, com limite de 8 MB por foto.</small></label>
      </div>
      <?php if ($edicao && $fotosEdicao): ?><div class="existing-photos"><strong>Fotos já publicadas</strong><div><?php foreach ($fotosEdicao as $foto): ?><form method="post" action="remover-foto-atualizacao.php" data-delete-confirm data-confirm-title="Remover esta foto?" data-confirm-message="A foto deixará de aparecer para o cliente. Esta ação não pode ser desfeita."><input type="hidden" name="obra_id" value="<?= (int) $obra['id'] ?>"><input type="hidden" name="foto_id" value="<?= (int) $foto['id'] ?>"><span><?= esc($foto['descricao']) ?></span><button class="text-action is-danger">Remover foto</button></form><?php endforeach; ?></div></div><?php endif; ?>
    </section>

    <footer class="form-actions field-save-bar"><a class="secondary-action" href="obras.php">Cancelar</a><button class="primary-action" type="submit" data-update-submit><?= $edicao ? 'Salvar alterações' : 'Publicar atualização' ?></button></footer>
  </form>

  <section class="update-history"><div class="history-heading"><div><h2>Atualizações publicadas</h2><p>Corrija ou exclua uma publicação quando precisar.</p></div><span><?= count($historico) ?></span></div><?php foreach ($historico as $item): ?><article><div><strong><?= dataCurta($item['data_atualizacao']) ?></strong><p><?= esc($item['resumo']) ?></p></div><div class="history-actions"><a class="secondary-action" href="atualizar-obra.php?id=<?= (int) $obra['id'] ?>&editar=<?= (int) $item['id'] ?>">Editar</a><form method="post" action="excluir-atualizacao-obra.php" data-delete-confirm data-confirm-title="Excluir esta atualização?" data-confirm-message="A atualização e todas as fotos dela deixarão de aparecer para o cliente. Esta ação não pode ser desfeita."><input type="hidden" name="obra_id" value="<?= (int) $obra['id'] ?>"><input type="hidden" name="atualizacao_id" value="<?= (int) $item['id'] ?>"><button class="text-action is-danger">Excluir</button></form></div></article><?php endforeach; ?></section>
</main><dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="confirm-delete-title"><div class="confirm-dialog-icon" aria-hidden="true">!</div><p class="eyebrow">Confirmação necessária</p><h2 id="confirm-delete-title" data-confirm-title>Excluir publicação?</h2><p data-confirm-message>Esta ação não pode ser desfeita.</p><div class="confirm-dialog-actions"><button class="secondary-action" type="button" data-confirm-cancel>Cancelar</button><button class="danger-action" type="button" data-confirm-accept>Excluir</button></div></dialog><script src="scripts/confirmacao-exclusao.js"></script><script src="scripts/fotos-atualizacao.js"></script><script src="scripts/atualizacao-obra.js"></script></body></html>

