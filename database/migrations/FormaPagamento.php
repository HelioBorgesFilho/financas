<?php

namespace migrations;

use core\Database;

class FormaPagamento {

    private $db;

    public function __construct(){

        $this->db = Database::getConnection();
    }

    public function up () {

        $sql = 'CREATE TABLE IF NOT EXISTS forma_pagamento (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(50),
                tipo VARCHAR(50),
                digito_cartao VARCHAR(4),
                usuario_criador_id INT,
                data INT,
                usuario_editor_id INT,
                data_edicao INT)';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }
}