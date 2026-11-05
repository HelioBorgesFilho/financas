<?php

namespace services;

use models\FormaPagamento;

class FormasPagamentoService {

    public function adicionarFormaPagamento(array $usuario, array $data) : array{

        $FormaPagamento = new FormaPagamento;

        $data['nome'] = isset($data['nome']) ? trim($data['nome']) : null;
        $data['tipo'] = isset($data['tipo']) ? trim($data['tipo']) : null;
        $data['digito_cartao'] = (isset($data['digito_cartao']) && trim($data['digito_cartao']) != null) ? trim($data['digito_cartao']) : null;
        $data['apelido_cartao'] = (isset($data['apelido_cartao']) && trim($data['apelido_cartao']) != null) ? trim($data['apelido_cartao']) : null;
        $data['usuario_criador'] = $usuario['id'];

        $formas = $FormaPagamento->metodosPagamentos($usuario['id']);   

        foreach($formas['mensagem'] as $forma){

            if($forma['nome'] == $data['nome'] && $data['usuario_criador'] == $forma['usuario_criador'] && $forma['tipo'] == $data['tipo']){

                if($forma['digito_cartao'] == $data['digito_cartao']){

                    return ['status' => false, 'codigo' => 409, 'mensagem' => 'Forma de pagamento já cadastrada.'];
                }                
            }
        }

        $cadastrar = $FormaPagamento->cadastrar($data);

        if($cadastrar['status']){

            $mensagem = [
                "id" => $cadastrar['id'],
                "nome" =>  $data['nome'],
                "tipo" => $data['tipo'],
            ];

            if($data['digito_cartao'] != null){

                $mensagem['digito_cartao'] = $data['digito_cartao'];
            }

            if($data['apelido_cartao'] != null){

                $mensagem['apelido_cartao'] =   $data['apelido_cartao'];
            }

            return ['status' => true, 'codigo' => 201, 'mensagem' => $mensagem];
        }

        return ['status' => false, 'codigo' => 500, 'mensagem' => 'Falha interna ao cadastrar'];
    }
}