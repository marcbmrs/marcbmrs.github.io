<?php
declare(strict_types=1);

/** Execute uma vez para habilitar o acompanhamento privado de obras. */
require __DIR__ . '/../app/db.php';

try {
    $pdo = banco();
    $pdo->exec("CREATE TABLE obras_acompanhamento (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(180) NOT NULL,
        cliente_nome VARCHAR(150) NOT NULL,
        localidade VARCHAR(120) NULL,
        status ENUM('em_andamento','pausada','concluida') NOT NULL DEFAULT 'em_andamento',
        proxima_etapa VARCHAR(255) NULL,
        token_acesso CHAR(64) NOT NULL UNIQUE,
        codigo_acesso_hash VARCHAR(255) NOT NULL,
        criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE atualizacoes_obra (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        obra_id BIGINT UNSIGNED NOT NULL,
        data_atualizacao DATE NOT NULL,
        status ENUM('em_andamento','pausada','concluida') NOT NULL DEFAULT 'em_andamento',
        resumo VARCHAR(500) NOT NULL,
        proximo_passo VARCHAR(255) NULL,
        criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_atualizacao_obra FOREIGN KEY (obra_id) REFERENCES obras_acompanhamento(id) ON DELETE CASCADE,
        INDEX idx_atualizacoes_obra_data (obra_id, data_atualizacao)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE fotos_atualizacao_obra (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        atualizacao_id BIGINT UNSIGNED NOT NULL,
        etapa ENUM('antes','durante','depois') NOT NULL DEFAULT 'durante',
        arquivo VARCHAR(255) NULL,
        descricao VARCHAR(180) NOT NULL,
        CONSTRAINT fk_foto_atualizacao FOREIGN KEY (atualizacao_id) REFERENCES atualizacoes_obra(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "Tabelas de acompanhamento de obras criadas.\n";
} catch (Throwable $erro) {
    fwrite(STDERR, $erro->getMessage() . "\n");
    exit(1);
}

