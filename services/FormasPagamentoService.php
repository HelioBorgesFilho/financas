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

        foreach($formas as $forma){

            if($forma['nome'] == $data['nome'] && $data['usuario_criador'] == $forma['usuario_criador'] && $forma['tipo'] == $data['tipo']){

                if($forma['digito_cartao'] == $data['digito_cartao'] && $forma['deletada'] != 1){

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

    public function atualizarFormaPagamento(array $data): array
    {

        $FormaPagamento = new FormaPagamento;

        $usuario = $_SESSION['usuario_id'];

        $forma = $FormaPagamento->detalhes($data['id']);

        if (!empty($forma["dados"])) {

            if ($forma["dados"]["id"] == $data['id'] && $forma["dados"]["usuario_criador"] == $usuario && $forma["dados"]["deletada"] == 0) {

                $data['nome'] = isset($data['nome']) && trim($data['nome']) !== '' ? trim($data['nome']) : $forma["dados"]['nome'];
                $data['tipo'] = isset($data['tipo']) && trim($data['tipo']) !== '' ? trim($data['tipo']) : $forma["dados"]["tipo"];
                $data['digito_cartao'] = array_key_exists('digito_cartao', $data)
                ? (trim($data['digito_cartao'] ?? '') === '' ? null : trim($data['digito_cartao']))
                : $forma['dados']['digito_cartao'];
                $data['apelido_cartao'] = array_key_exists('apelido_cartao', $data)
                ? (trim($data['apelido_cartao'] ?? '') === '' ? null : trim($data['apelido_cartao']))
                : $forma['dados']['apelido_cartao'];              
                $data['usuario_editor'] = $usuario;

                $atualizado = $FormaPagamento->atualizar($data['id'], $data);

                return $atualizado;
            }

            return ['status' => false, 'mensagem' => 'Forma já atualizada ou não pertencente ao usuário', 'codigo' => 409];
        }

        return ['status' => false, 'mensagem' => 'Forma de pagamento não encontrada.', 'codigo' => 409];
    }
}