<?php
declare(strict_types=1);

require __DIR__ . '/../app/db.php';

$pdo = banco();
$coluna = $pdo->query("SHOW COLUMNS FROM orcamentos LIKE 'prazo_dias'")->fetch();

if (!$coluna) {
    $pdo->exec('ALTER TABLE orcamentos ADD COLUMN prazo_dias SMALLINT UNSIGNED NULL AFTER validade');
    echo "Coluna prazo_dias criada.\n";
} else {
    echo "Coluna prazo_dias já existe.\n";
}

