<?php

namespace migrations;

use core\Database;

class CategoriaLancamentos {

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function up () {

        $sql = 'CREATE TABLE IF NOT EXISTS categoria_lancamentos (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        codigo VARCHAR(50),
        nome VARCHAR(50),
        usuario_criador_id INT,
        data INT,
        usuario_editor_id INT,
        data_edicao INT)';  
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }
}