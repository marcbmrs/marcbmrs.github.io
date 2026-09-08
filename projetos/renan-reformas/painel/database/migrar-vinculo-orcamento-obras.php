<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';

$pdo = banco();
if (!$pdo->query("SHOW COLUMNS FROM obras_acompanhamento LIKE 'orcamento_id'")->fetch()) {
    $pdo->exec('ALTER TABLE obras_acompanhamento ADD COLUMN orcamento_id BIGINT UNSIGNED NULL AFTER id');
    $pdo->exec('ALTER TABLE obras_acompanhamento ADD CONSTRAINT fk_obra_orcamento FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id)');
    $pdo->exec('ALTER TABLE obras_acompanhamento ADD UNIQUE INDEX uq_obras_orcamento (orcamento_id)');
}
echo "Vínculo entre orçamento e obra pronto.\n";
