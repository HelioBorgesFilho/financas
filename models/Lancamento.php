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

        $sql = 'SELECT * FROM lancamentos WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }


    public function salvar($data)
    {
        $sql = 'INSERT INTO lancamentos (id, id_grupo, tipo, valor_centavos, descricao, local, forma_pagamento_id, data, categoria, em_nome_de_id, repeticao, comprovante_ids) 
            VALUES (:id, :id_grupo, :tipo, :valor_centavos, :descricao, :local, :forma_pagamento_id, :data, :categoria, :em_nome_de_id, :repeticao, :comprovante_ids   ) 
            ON DUPLICATE KEY UPDATE lancamentos = lancamentos + 1';


        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $data->id);
        $stmt->bindValue(':id_grupo', $data->id_grupo);
        $stmt->bindValue(':tipo', $data->tipo);
        $stmt->bindValue(':valor_centavos', $data->valor_centavos);
        $stmt->bindValue(':descricao', $data->descricao);
        $stmt->bindValue(':local', $data->local);
        $stmt->bindValue(':forma_pagamento_id', $data->forma_pagamento_id);
        $stmt->bindValue(':data', $data->data);
        $stmt->bindValue(':categoria', $data->categoria);
        $stmt->bindValue(':em_nome_de_id', $data->em_nome_de_id);
        $stmt->bindValue(':repeticao', $data->repeticao);
        $stmt->bindValue(':comprovante_ids', $data->comprovante_ids);
        $stmt->execute();

        return  $this->db->lastInsertId();
    }




    public function excluir(int $id)
    {
        $sql = "UPDATE lancamentos SET deletado = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function listarPorGrupo(?int $id_grupo = null): array
    {
        $sql = "SELECT id, id_grupo, tipo, valor_centavos, descricao, local, forma_pagamento_id, data, categoria, em_nome_de_id, repeticao, comprovante_ids FROM lancamentos";

        if ($id_grupo != null) {
            $sql .= ' WHERE id_grupo = :id_grupo';
        }

        $sql .= ' ORDER BY criado_em DESC';

        $stmt = self::$db->prepare($sql);

        if ($id_grupo != null) {
            $stmt->bindValue(':id_grupo', $id_grupo, \PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

