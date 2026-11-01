<?php

namespace models;

use core\Database;

class GrupoFamilia {

    private $db;

    private function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function criar(array $dados) : array {

        try{

            $sql = 'INSERT INTO grupo_familia (nome, limite_mensal_centavos, usuario_criador, data_cadastro) VALUES (:nome, :limite_mensal_centavos, :usuario_criador, :data_cadastro)';

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':limite_mensal_centavos', $dados['limite_mensal_centavos']);
            $stmt->bindValue(':usuario_criador', $dados['usuario_criador']);
            $stmt->bindValue(':data_cadastro', time());

            $stmt->execute();

            return [
                'status' => true,
            ];

        }catch(\PDOException $e){

            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }

    public function editar(array $dados) : array {

        try{

            $sql = 'UPDATE grupo_familia SET nome = :nome, limite_mensal_centavos = :limite_mensal_centavos, usuario_editor = :usuario_editor, data_modificacao = :data_modificacao WHERE id = :id';

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':limite_mensal_centavos', $dados['limite_mensal_centavos']);
            $stmt->bindValue(':usuario_editor', $dados['usuario_editor']);
            $stmt->bindValue(':data_modificacao', time());

            $stmt->execute();

            $alterado = $stmt->rowCount() > 0;

            return [
                'status' => $alterado,
                'mensagem' => $alterado
                    ? 'Dados alterados com sucesso!'
                    : 'Nenhum dado foi alterado.'
            ];

        }catch(\PDOException $e){

            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }

    public function remover(int $id) : array {

        try{

            $sql = 'UPDATE grupo_familia SET deletado = :deletado WHERE id = :id';

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':deletado', 1);

            $stmt->execute();

            $deletado = $stmt->rowCount() > 0;

            return [
                'status' => $deletado,
                'mensagem' => $deletado
                    ? 'Deletado com sucesso!'
                    : 'Nenhum dado foi deletado.'
            ];

        }catch(\PDOException $e){

            return [
                'status' => false,
                'mensagem' => $e->errorInfo
            ];
        }
    }

    public function detalhes(int $id) {

        $sql = 'SELECT id, nome, limite_mensal_centavos, deletado FROM grupo_familia WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}