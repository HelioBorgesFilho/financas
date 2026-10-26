CREATE TABLE IF NOT EXISTS forma_pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    digito_cartao VARCHAR(4) DEFAULT NULL,
    apelido_cartao VARCHAR(20) DEFAULT NULL,
    usuario_criador INT NOT NULL,
    data_cadastro INT NOT NULL,
    usuario_editor INT DEFAULT NULL,
    data_modificacao INT DEFAULT NULL,
    deletada TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;