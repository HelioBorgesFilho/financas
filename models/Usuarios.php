<?php

namespace models;

use core\Database;

class Usuarios {

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection(); 
    }

    public function cadastrar($nome, $email, $senha, $idade, $codigoEmail, $codigoConvite){

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

    public function atualizarInfo($id, $dados){

        try {

            $sql = 'UPDATE usuarios SET nome = :nome, email = :email, idade = :idade, nivel_acesso = :nivel_acesso, assinatura = :assinatura, id_familia = :id_familia, data_atualizacao = :data_atualizacao WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':idade', $dados['idade']);
            $stmt->bindValue(':nivel_acesso', $dados['nivel_acesso']);
            $stmt->bindValue(':assinatura', $dados['assinatura']);
            $stmt->bindValue(':id_familia', $dados['id_familia']);
            $stmt->bindValue(':data_atualizacao', $dados['data_atualizacao']);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $status = true;
                $mensagem = 'Dados alterados com sucesso!';
            } else {

                $status = false;
                $mensagem = 'Nenhum dado foi alterado.';
            }

            return ['status' => $status, 'mensagem' => $mensagem];
            

        } catch (\PDOException $e) {

            return ['status' => false, 'mensagem' => $e->errorInfo];
        }
    }

    public function atualizarFoto($id, $url){

        try {

            $sql = 'UPDATE usuarios SET url = :url WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':url', $url);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $status = true;
                $mensagem = 'Dados alterados com sucesso!';
            } else {

                $status = false;
                $mensagem = 'Nenhum dado foi alterado.';
            }

            return ['status' => $status, 'mensagem' => $mensagem];
            

        } catch (\PDOException $e) {

            return ['status' => false, 'mensagem' => $e->errorInfo];
        }
    }

    public function confirmarEmail($id, $email, $codigo){

        try {

            $sql = 'UPDATE usuarios SET email_pendente = :email, codigo = :codigo WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':email_pendente', $email);
            $stmt->bindValue(':codigo', $codigo);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $status = true;
                $mensagem = 'Dados alterados com sucesso!';
            } else {

                $status = false;
                $mensagem = 'Nenhum dado foi alterado.';
            }

            return ['status' => $status, 'mensagem' => $mensagem];


        } catch (\PDOException $e) {

            return ['status' => false, 'mensagem' => $e->errorInfo];
        }
    }

    public function atualizarSenha($id, $email, $senha){

        try {

            $sql = 'UPDATE usuarios SET email = :email, senha = :senha WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senha);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $status = true;
                $mensagem = 'Dados alterados com sucesso!';
            } else {

                $status = false;
                $mensagem = 'Nenhum dado foi alterado.';
            }

            return ['status' => $status, 'mensagem' => $mensagem];


        } catch (\PDOException $e) {

            return ['status' => false, 'mensagem' => $e->errorInfo];
        }
    }

    public function detalhes($id) {

        $sql = 'SELECT id, nome, email, email_pendente, url_foto, cor, idade, assinatura FROM usuarios WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC)  ;

        return $usuario;
    }

    public function usuario($email){

        $sql = 'SELECT id, nome, email, senha, email_pendente, url_foto, cor, idade, assinatura FROM usuarios WHERE email = :email';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $usuario;
    }
}