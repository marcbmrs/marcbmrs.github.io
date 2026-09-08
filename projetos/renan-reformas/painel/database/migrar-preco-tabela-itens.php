<?php
declare(strict_types=1);

/** Execute uma vez nas instalações existentes do painel. */
require __DIR__ . '/../app/db.php';

try {
    $pdo = banco();
    $pdo->exec('ALTER TABLE itens_orcamento ADD valor_tabela_centavos BIGINT UNSIGNED NULL AFTER periodo_tipo');
    $pdo->exec('UPDATE itens_orcamento SET valor_tabela_centavos = valor_unitario_centavos WHERE valor_tabela_centavos IS NULL');
    $pdo->exec('ALTER TABLE itens_orcamento MODIFY valor_tabela_centavos BIGINT UNSIGNED NOT NULL');
    echo "Preço de tabela adicionado aos itens do orçamento.\n";
} catch (Throwable $erro) {
    fwrite(STDERR, $erro->getMessage() . "\n");
    exit(1);
}
