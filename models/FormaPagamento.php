<?php

namespace models;

use core\Database;
use PDOException;


class FormaPagamento
{

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function cadastrar($dados)
    {

        try {
            $sql = 'INSERT INTO forma_pagamento (nome, tipo, digito_cartao, apelido_cartao, usuario_criador, data_cadastro ) VALUES (:nome, :tipo, :digito_cartao, :apelido_cartao, :usuario_criador, :data_cadastro )';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nome' => $dados['nome'],
                ':tipo' => $dados['tipo'],
                ':digito_cartao' => $dados['digito_cartao'],
                ':apelido_cartao' => $dados['apelido_cartao'],
                ':usuario_criador' => $dados['usuario_criador'],
                ':data_cadastro' => time(),
            ]);

            return [
                'status' => true,
                'id' => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }
    public function atualizar($id, $dados)
    {
        try {
            $sql = "UPDATE forma_pagamento SET nome = :nome, tipo = :tipo, digito_cartao = :digito_cartao, apelido_cartao = :apelido_cartao, usuario_editor = :usuario_editor, data_modificacao = :data_modificacao WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':nome' => $dados['nome'],
                ':tipo' => $dados['tipo'],
                ':digito_cartao' => $dados['digito_cartao'],
                ':apelido_cartao' => $dados['apelido_cartao'],
                ':usuario_editor' => $dados['usuario_editor'],
                ':data_modificacao' => time()
            ]);

            $alterado = $stmt->rowCount() > 0;

            return [
                'status' => $alterado,
                'mensagem' => $alterado
                    ? 'Dados alterados com sucesso!'
                    : 'Nenhum dado foi alterado.',
                'codigo' => 200
            ];
        } catch (PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo,
                'codigo' => 500
            ];
        }
    }
    public function remover($id)
    {
        try {
            $sql = 'UPDATE forma_pagamento SET deletada = :deletada WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->bindValue(':deletada', 1, \PDO::PARAM_INT);
            $stmt->execute();

            return [
                'status' => true,
                'mensagem' => 'Forma deletada'
            ];
        } catch (PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }
    public function listar()
    {
        try {
            $sql = 'SELECT * FROM forma_pagamento';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'mensagem' => $dados
            ];
        } catch (PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }
    public function detalhes($id)
    {
        try {
            $sql = 'SELECT * FROM forma_pagamento WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(\PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'dados' => $dados ?: []
            ];
        } catch (PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }

    public function metodosPagamentos(int $id) : array
    {

        $sql = 'SELECT * FROM forma_pagamento WHERE usuario_criador = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $dados;
    }
}
