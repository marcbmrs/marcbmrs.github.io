<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';

banco()->exec("CREATE TABLE IF NOT EXISTS usuarios_equipe (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  ultimo_acesso_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
echo "Tabela de usuários da equipe criada.\n";
