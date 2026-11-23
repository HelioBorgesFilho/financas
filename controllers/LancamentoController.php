<?php

namespace controllers;

use controllers\Resposta;
use controllers\Validacao;

class LancamentoController {

    public function verificarLancamento(array $data){

        $Resposta = new Resposta;
        $Validacao = new Validacao;

        if (empty($data)) {
            return $Resposta->modeloRespostaErro(400,  ['erro' => 'JSON mal formado.'], 'validação');
        }

        $acao = 'lancamento';

        if(isset($data['tipo']) && trim($data['tipo']) == 'despesa'){

            $acao = 'despesa';
        }

        $campos = $Validacao->camposObrigatorios($acao, $data);

        if($campos['validado']){

            return $Resposta->resposta(200, 'ok');
        }        

        return $Resposta->modeloRespostaErro(401, 'Campos devem ser preenchidos', 'validação', $acao, $campos['dados']);
    }
}