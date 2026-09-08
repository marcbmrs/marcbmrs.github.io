<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';
$pdo = banco();
if (!$pdo->query("SHOW COLUMNS FROM obras_acompanhamento LIKE 'codigo_acesso_exibicao'")->fetch()) {
    $pdo->exec('ALTER TABLE obras_acompanhamento ADD COLUMN codigo_acesso_exibicao CHAR(6) NULL AFTER codigo_acesso_hash');
}
$pdo->prepare('UPDATE obras_acompanhamento SET codigo_acesso_exibicao = ? WHERE cliente_nome = ? AND codigo_acesso_exibicao IS NULL')->execute(['482913', 'Cliente demonstração']);
echo "Código operacional de acesso pronto.\n";

