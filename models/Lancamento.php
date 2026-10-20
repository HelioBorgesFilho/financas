<?php

namespace models;

use core\Database;


class Lancamento
{

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function carregar(int $id)
    {
        try {
            $sql = 'SELECT * FROM lancamentos WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(\PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'mensagem' => 'Lançamento carregado com sucesso.',
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

    public function salvar($data)
    {
        try {
            $sql = 'INSERT INTO lancamentos (tipo, descricao, local, forma_pagamento_id, data, data_edicao, categoria_id, usuario_lancamento_id, usuario_atrubuido_id, repeticao, comprovante_ids) VALUES (:tipo, :descricao, :local, :forma_pagamento_id, :data, :data_edicao, :categoria_id, :usuario_lancamento_id, :usuario_atrubuido_id, :repeticao, :comprovante_ids)';

            $stmt = $this->db->prepare($sql);
            $status = $stmt->execute([
                ':tipo' => $data['tipo'],
                ':descricao' => $data['descricao'],
                ':local' => $data['local'],
                ':forma_pagamento_id' => $data['forma_pagamento_id'],
                ':data' => $data['data'],
                ':data_edicao' => $data['data_edicao'],
                ':categoria_id' => $data['categoria_id'],
                ':usuario_lancamento_id' => $data['usuario_lancamento_id'],
                ':usuario_atrubuido_id' => $data['usuario_atrubuido_id'],
                ':repeticao' => $data['repeticao'],
                ':comprovante_ids' => $data['comprovante_ids']
            ]);

            return [
                'status' => $status,
                'mensagem' => $status ? 'Lançamento salvo com sucesso.' : 'Erro ao salvar lançamento.',
                'id_inserido' => $this->db->lastInsertId()
            ];
        } catch (\PDOException $e) {
            return [
                'status' => false,
                'mensagem' => $e->errorInfo,
                'id_inserido' => null
            ];
        }
    }

    public function excluir(int $id)
    {
        try {
            $sql = "DELETE FROM lancamentos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $status = $stmt->execute();

            return [
                'status' => $status,
                'mensagem' => $status ? 'Lançamento excluído com sucesso.' : 'Erro ao excluir lançamento.'
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
            $sql = "SELECT * FROM lancamentos ORDER BY id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'mensagem' => 'Lançamentos listados com sucesso.',
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
}
