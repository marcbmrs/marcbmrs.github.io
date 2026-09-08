CREATE DATABASE IF NOT EXISTS renan_reformas_painel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE renan_reformas_painel;

CREATE TABLE usuarios_equipe (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  ultimo_acesso_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE sessoes_lembradas (
  seletor CHAR(24) PRIMARY KEY,
  usuario_id BIGINT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expira_em DATETIME NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_sessao_lembrada_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios_equipe(id) ON DELETE CASCADE,
  INDEX idx_sessoes_lembradas_expira (expira_em)
) ENGINE=InnoDB;

CREATE TABLE tentativas_login (
  email_hash CHAR(64) NOT NULL,
  ip_hash CHAR(64) NOT NULL,
  tentativas TINYINT UNSIGNED NOT NULL DEFAULT 0,
  primeira_tentativa_em DATETIME NOT NULL,
  bloqueado_ate DATETIME NULL,
  PRIMARY KEY (email_hash, ip_hash),
  INDEX idx_tentativas_bloqueio (bloqueado_ate)
) ENGINE=InnoDB;

CREATE TABLE clientes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  telefone VARCHAR(30) NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_clientes_nome (nome)
) ENGINE=InnoDB;

CREATE TABLE orcamentos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(24) NOT NULL UNIQUE,
  cliente_id BIGINT UNSIGNED NOT NULL,
  local_obra VARCHAR(180) NULL,
  cidade VARCHAR(100) NULL,
  bairro VARCHAR(100) NULL,
  endereco VARCHAR(180) NULL,
  complemento VARCHAR(180) NULL,
  cep VARCHAR(9) NULL,
  validade VARCHAR(80) NULL,
  prazo_dias SMALLINT UNSIGNED NULL,
  status ENUM('rascunho','pronto_para_envio','aguardando_cliente','negociacao','aprovado','recusado','expirado','cancelado') NOT NULL DEFAULT 'rascunho',
  revisao SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  origem_id BIGINT UNSIGNED NULL,
  total_centavos BIGINT UNSIGNED NOT NULL DEFAULT 0,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orcamento_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_orcamento_origem FOREIGN KEY (origem_id) REFERENCES orcamentos(id),
  INDEX idx_orcamentos_cliente (cliente_id), INDEX idx_orcamentos_status (status)
) ENGINE=InnoDB;

CREATE TABLE itens_orcamento (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  orcamento_id BIGINT UNSIGNED NOT NULL,
  categoria ENUM('mao_de_obra','equipamento') NOT NULL,
  descricao VARCHAR(180) NOT NULL,
  unidade VARCHAR(40) NOT NULL,
  quantidade DECIMAL(10,2) NOT NULL,
  periodo_quantidade DECIMAL(10,2) NULL,
  periodo_tipo ENUM('diaria','mensal','peca') NULL,
  valor_tabela_centavos BIGINT UNSIGNED NOT NULL,
  valor_unitario_centavos BIGINT UNSIGNED NOT NULL,
  subtotal_centavos BIGINT UNSIGNED NOT NULL,
  CONSTRAINT fk_item_orcamento FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE obras_acompanhamento (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  orcamento_id BIGINT UNSIGNED NULL,
  titulo VARCHAR(180) NOT NULL,
  cliente_nome VARCHAR(150) NOT NULL,
  localidade VARCHAR(120) NULL,
  status ENUM('em_andamento','pausada','concluida') NOT NULL DEFAULT 'em_andamento',
  proxima_etapa VARCHAR(255) NULL,
  token_acesso CHAR(64) NOT NULL UNIQUE,
  codigo_acesso_hash VARCHAR(255) NOT NULL,
  codigo_acesso_exibicao CHAR(6) NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ,CONSTRAINT fk_obra_orcamento FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id)
  ,UNIQUE INDEX uq_obras_orcamento (orcamento_id)
) ENGINE=InnoDB;

CREATE TABLE atualizacoes_obra (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  obra_id BIGINT UNSIGNED NOT NULL,
  data_atualizacao DATE NOT NULL,
  status ENUM('em_andamento','pausada','concluida') NOT NULL DEFAULT 'em_andamento',
  resumo VARCHAR(500) NOT NULL,
  proximo_passo VARCHAR(255) NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_atualizacao_obra FOREIGN KEY (obra_id) REFERENCES obras_acompanhamento(id) ON DELETE CASCADE,
  INDEX idx_atualizacoes_obra_data (obra_id, data_atualizacao)
) ENGINE=InnoDB;

CREATE TABLE fotos_atualizacao_obra (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  atualizacao_id BIGINT UNSIGNED NOT NULL,
  etapa ENUM('antes','durante','depois') NOT NULL DEFAULT 'durante',
  arquivo VARCHAR(255) NULL,
  descricao VARCHAR(180) NOT NULL,
  CONSTRAINT fk_foto_atualizacao FOREIGN KEY (atualizacao_id) REFERENCES atualizacoes_obra(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE minutas_contrato (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  orcamento_id BIGINT UNSIGNED NOT NULL UNIQUE,
  conteudo LONGTEXT NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_minuta_orcamento FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE configuracoes_contrato (
  chave VARCHAR(60) PRIMARY KEY,
  valor LONGTEXT NOT NULL,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
