CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) DEFAULT NULL,
    codigo_email VARCHAR(100) DEFAULT NULL,
    email_pendente VARCHAR(150) DEFAULT NULL,
    codigo_convite VARCHAR(20) DEFAULT NULL,
    idade INT DEFAULT NULL,
    nivel_acesso VARCHAR(20) DEFAULT NULL,
    assinatura VARCHAR(20) NOT NULL DEFAULT 'standard',
    id_familia INT DEFAULT NULL,
    url_foto VARCHAR(255) DEFAULT NULL,
    data_cadastro INT NOT NULL,
    data_atualizacao INT DEFAULT NULL,
    cor VARCHAR(7) DEFAULT NULL,
    token_redefinir_senha VARCHAR(100) DEFAULT NULL,
    assinante_via VARCHAR(20) DEFAULT NULL,
    assinatura_recibo VARCHAR(255) DEFAULT NULL,
    deletado TINYINT(1) NOT NULL DEFAULT 0,

    INDEX idx_id_familia (id_familia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;