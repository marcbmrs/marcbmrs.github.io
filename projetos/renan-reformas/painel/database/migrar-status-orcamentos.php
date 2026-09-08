<?php
declare(strict_types=1);

/**
 * Migração única para instalações que já possuem a tabela orcamentos.
 * Execute localmente uma vez: php database/migrar-status-orcamentos.php
 */
require __DIR__ . '/../app/db.php';

$pdo = banco();
try {
    $pdo->exec("ALTER TABLE orcamentos MODIFY status ENUM('rascunho','enviado','negociacao','aprovado','recusado','concluido','pronto_para_envio','aguardando_cliente','expirado','cancelado') NOT NULL DEFAULT 'rascunho'");
    $pdo->exec("UPDATE orcamentos SET status = 'aguardando_cliente' WHERE status = 'enviado'");
    $pdo->exec("UPDATE orcamentos SET status = 'aprovado' WHERE status = 'concluido'");
    $pdo->exec("ALTER TABLE orcamentos MODIFY status ENUM('rascunho','pronto_para_envio','aguardando_cliente','negociacao','aprovado','recusado','expirado','cancelado') NOT NULL DEFAULT 'rascunho'");
    echo "Status dos orçamentos atualizados.\n";
} catch (Throwable $erro) {
    fwrite(STDERR, $erro->getMessage() . "\n");
    exit(1);
}
