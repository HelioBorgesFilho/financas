CREATE TABLE IF NOT EXISTS lancamentos (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
tipo VARCHAR(50) NOT NULL,
descricao VARCHAR (100) DEFAULT NULL,
local VARCHAR (100) NOT NULL,
forma_pagamento_id INT NOT NULL,
data INT,
data_edicao INT,
categoria_id INT,
usuario_lancamento_id INT NOT NULL,
usuario_atrubuido_id INT NOT NULL,
repeticao VARCHAR(20) DEFAULT NULL,
comprovante_ids VARCHAR(255) DEFAULT NULL)