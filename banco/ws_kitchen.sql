-- ============================================================
--  WS Kitchen — Schema completo
--  Compatível com MySQL / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS ws_kitchen
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ws_kitchen;

-- ------------------------------------------------------------
-- Clientes
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(120)  NOT NULL,
  email      VARCHAR(180)  NOT NULL UNIQUE,
  senha      VARCHAR(255)  NOT NULL,
  criado_em  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Pedidos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedidos (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id     INT UNSIGNED NULL,                        -- NULL = anônimo
  nome_anonimo   VARCHAR(120) NULL,                        -- nome da comanda anônima
  total          DECIMAL(10,2) NOT NULL DEFAULT 0,
  status         ENUM('Pendente','Preparando','Saiu','Entregue','Cancelado')
                   NOT NULL DEFAULT 'Pendente',
  mesa           SMALLINT UNSIGNED NULL,
  tempo_estimado SMALLINT UNSIGNED NULL,                   -- minutos
  observacao     TEXT NULL,
  itens          JSON NULL,                                -- snapshot rápido dos itens
  data_pedido    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                   ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES clientes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Itens do pedido  (tabela normalizada — nome ÚNICO sem "s")
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS itens_pedido (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id  INT UNSIGNED NOT NULL,
  nome       VARCHAR(120)  NOT NULL,
  preco      DECIMAL(8,2)  NOT NULL,
  quantidade SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- índice para acelerar busca de itens por pedido
CREATE INDEX IF NOT EXISTS idx_itens_pedido_id ON itens_pedido(pedido_id);
