<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';
banco()->exec("CREATE TABLE IF NOT EXISTS configuracoes_contrato (
    chave VARCHAR(60) PRIMARY KEY,
    valor LONGTEXT NOT NULL,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "Configuração do modelo-base pronta.\n";
