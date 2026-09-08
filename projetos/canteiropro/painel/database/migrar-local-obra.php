<?php
declare(strict_types=1);

/** Execute uma vez em instalações existentes após migrar os status. */
require __DIR__ . '/../app/db.php';

try {
    $pdo = banco();
    $pdo->exec('ALTER TABLE orcamentos ADD cidade VARCHAR(100) NULL AFTER local_obra, ADD bairro VARCHAR(100) NULL AFTER cidade, ADD endereco VARCHAR(180) NULL AFTER bairro, ADD complemento VARCHAR(180) NULL AFTER endereco, ADD cep VARCHAR(9) NULL AFTER complemento');
    echo "Campos de localização da obra adicionados.\n";
} catch (Throwable $erro) {
    fwrite(STDERR, $erro->getMessage() . "\n");
    exit(1);
}

