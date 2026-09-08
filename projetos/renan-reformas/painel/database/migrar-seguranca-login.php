<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';
banco()->exec("CREATE TABLE IF NOT EXISTS tentativas_login (
  email_hash CHAR(64) NOT NULL,
  ip_hash CHAR(64) NOT NULL,
  tentativas TINYINT UNSIGNED NOT NULL DEFAULT 0,
  primeira_tentativa_em DATETIME NOT NULL,
  bloqueado_ate DATETIME NULL,
  PRIMARY KEY (email_hash, ip_hash),
  INDEX idx_tentativas_bloqueio (bloqueado_ate)
) ENGINE=InnoDB");
echo "Proteção contra tentativas de login criada.\n";
