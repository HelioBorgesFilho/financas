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
            $sql = 'INSERT INTO forma_pagamento (nome, tipo, digito_cartao, apelido_cartao, usuario_criador, data_cadastro, usuario_editor, data_modificacao, deletada) VALUES (:nome, :tipo, :digito_cartao, :apelido_cartao, :usuario_criador, :data_cadastro, :usuario_editor, :data_modificacao, :deletada)';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nome' => $dados['nome'],
                ':tipo' => $dados['tipo'],
                ':digito_cartao' => $dados['digito_cartao'],
                ':apelido_cartao' => $dados['apelido_cartao'],
                ':usuario_criador' => $dados['usuario_criador'],
                ':data_cadastro' => $dados['data_cadastro'],
                ':usuario_editor' => $dados['usuario_editor'],
                ':data_modificacao' => $dados['data_modificacao'],
                ':deletada' => (int) ($dados['deletada'])
            ]);

            return [
                'status' => true,
                'mensagem' => 'Forma de pagamento cadastrada com sucesso.'
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
            $sql = "UPDATE forma_pagamento SET nome = :nome,  digito_cartao = :digito_cartao, apelido_cartao = :apelido_cartao, usuario_editor = :usuario_editor, data_modificacao = :data_modificacao WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':nome' => $dados['nome'],
                ':digito_cartao' => $dados['digito_cartao'],
                ':apelido_cartao' => $dados['apelido_cartao'],
                ':usuario_editor' => $dados['usuario_editor'],
                ':data_modificacao' => $dados['data_modificacao']
            ]);

            return [
                'status' => true,
                'mensagem' => 'Forma de pagamento atualizada com sucesso.'
            ];
        } catch (\PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }
    public function remover($id)
    {
        try {
            $sql = 'UPDATE forma_pagamento SET deletada = 1 WHERE id = :id';

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $status = $stmt->execute();

            return [
                'status' => $status,
                'mensagem' => $status ? 'Forma de pagamento removida com sucesso.' : 'Erro ao remover forma de pagamento.'
            ];
        } catch (\PDOException $e) {
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
                'mensagem' => 'Formas de pagamento listadas com sucesso.',
                'dados' => $dados
            ];
        } catch (\PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo,
                'dados' => []
            ];
        }
    }
    public function detalhes($id)
    {
        try {
            $sql = 'SELECT * FROM forma_pagamento WHERE id = :id';

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $status = $stmt->execute();
            $dados = $stmt->fetch(\PDO::FETCH_ASSOC);

            return [
                'status' => $status,
                'mensagem' => $dados ? 'Detalhes recuperados com sucesso.' : 'Nenhum registro encontrado.',
                'dados' => $dados
            ];
        } catch (\PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo,
                'dados' => null
            ];
        }
    }
}
