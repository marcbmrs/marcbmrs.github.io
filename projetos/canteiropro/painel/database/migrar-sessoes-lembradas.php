<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';

banco()->exec("CREATE TABLE IF NOT EXISTS sessoes_lembradas (
  seletor CHAR(24) PRIMARY KEY,
  usuario_id BIGINT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expira_em DATETIME NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_sessao_lembrada_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios_equipe(id) ON DELETE CASCADE,
  INDEX idx_sessoes_lembradas_expira (expira_em)
) ENGINE=InnoDB");
echo "Tabela de sessões lembradas criada.\n";

