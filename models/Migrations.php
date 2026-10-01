<?php

namespace models;

use core\Database;

class Migrations {

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function migrate ($sql) {

        $smtm = $this->db->prepare($sql);
        $teste = $smtm->execute(); 
        
        return $teste;
    }
}