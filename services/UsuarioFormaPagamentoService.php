<?php

namespace services;

use models\FormaPagamento;
use controllers\Resposta;

class UsuarioFormaPagamentoService {

    public function formaPagamentoCadastradas(){

        $FormaPagamento = new FormaPagamento;
        $Resposta = new Resposta;

        $usuario = $_SESSION['usuario_id'];

        $formas = $FormaPagamento->metodosPagamentos($usuario);

        if(!empty($formas)) {

            $opcoesFormas = [];

            foreach($formas as $posicao => $forma){

                $opcoesFormas[$posicao] = ['id' => $forma['id'], 'nome' => $forma['nome'], 'tipo' => $forma['tipo']];

                if($forma['digito_cartao'] != null){
                    $opcoesFormas[$posicao]['digito_cartao'] = $forma['digito_cartao'];
                }
                if($forma['apelido_cartao'] != null){
                    $opcoesFormas[$posicao]['apelido_cartao'] = $forma['apelido_cartao'];
                }
            }

            return $Resposta->resposta(200, $opcoesFormas);
        }

        return $Resposta->modeloRespostaErro(200, 'Não possue formas de pagamento cadastradas.');
    }
}