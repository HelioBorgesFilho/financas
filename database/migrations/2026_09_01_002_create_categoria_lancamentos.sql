CREATE TABLE IF NOT EXISTS categoria_lancamentos (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
codigo VARCHAR(50),
nome VARCHAR(50),
usuario_criador_id INT,
data INT,
usuario_editor_id INT,
data_edicao INT)