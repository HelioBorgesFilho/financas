<?php

namespace models;

use core\Database;

class Usuarios {

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection(); 
    }

    public function novoRegistro ($nome, $email, $senha, $idade, $codigoEmail, $codigoConvite){

        try {

            $sql = 'INSERT INTO usuarios (nome, email, codigo_email, senha, idade, codigo_convite, data_cadastro) VALUES (:nome, :email, :codigo_email, :senha, :idade, :codigo_convite, :data_cadastro)';

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senha);
            $stmt->bindValue(':idade', $idade);
            $stmt->bindValue(':codigo_email', $codigoEmail);
            $stmt->bindValue(':codigo_convite', $codigoConvite);
            $stmt->bindValue(':data_cadastro', time(), \PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => true, 'usuario' => $this->db->lastInsertId(), 'mensagem' => 'Cadastrado com sucesso!'];

        } catch (\PDOException $e) {

            return ['status' => false, 'mensagem' => $e->errorInfo];
        }
    }

    public function usuario($id){

        $sql = 'SELECT * FROM usuarios WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $usuario;
    }
}