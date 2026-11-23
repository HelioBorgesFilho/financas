<?php

namespace controllers;

class Resposta {

    public function resposta($codigo, $mensagem): array
    {
        return ['codigo' => $codigo, 'mensagem' => $mensagem];
    }

    public function mensagensPadraoErro(string $acao, $nomeMensagem): string{

        $mensagens = [

            'cadastroUsuario' => ['nome' => 'Informe um nome com pelo menos 2 letras.', 'email' => 'Informe o e-mail.', 'senha' => 'Use pelo menos 8 caracteres.', 'idade' => 'Informe sua idade.',],

            'editarFormaPagamento' => ['digito_cartao' => 'Deve conter exatamente 4 dígitos numéricos.'],

            'log' => ['email' => 'Informe o e-mail.', 'senha' => 'Use pelo menos 8 caracteres.'],

            'cadastrarFormaPagamento' => ['email' => 'Informe o e-mail.', 'tipo' => 'Escolha o tipo da forma de pagamento.'],

            'lancamento' => ['tipo' => 'Escolha um tipo.', 'descricao' => 'Escolha uma descrição.', 'local' => 'Escolha uma descrição.', 'valor_centavos' => 'Escolha uma descrição.', 'forma_pagamento_id' => 'Escolha uma descrição.', 'data' => 'Escolha uma descrição.', 'categoria' => 'Escolha uma descrição.', 'em_nome_de_id' => 'Escolha uma descrição.', 'lancado_por_id' => 'Escolha uma descrição.'],

            'despesa' => ['tipo' => 'Escolha uma descrição.', 'descricao' => 'Escolha uma descrição.', 'valor_centavos' => 'Escolha uma descrição.', 'data' => 'Escolha uma descrição.', 'categoria' => 'Escolha uma descrição.', 'em_nome_de_id' => 'Escolha uma descrição.', 'lancado_por_id' => 'Escolha uma descrição.'],
        ];

        $mensagem = '';

        foreach($mensagens[$acao] as $posicao => $msg){

            if(isset($mensagens[$acao][$posicao])){

                if($posicao == $nomeMensagem){

                    $mensagem = $msg;
                }
            }else{

                $mensagem = 'Campo não encontrado, informe ao desenvolvedor.';
            }
        }
        return $mensagem;
    }

    public function modeloRespostaErro(int $codigo, $mensagem, ?string $motivo = null, ?string $acao = null, $campos = null): array {

        $respostaErro = ['erro' => ['mensagem' => $mensagem]];

        if($motivo != null){

            $respostaErro = ['erro' => ['motivo' => $motivo,'mensagem' => $mensagem]];
        }       

        if($campos){

            foreach($campos as $posicao => $campo){  
                
                if(trim($campo) == null){

                    $respostaErro["erro"]['campos'][$posicao] = $this->mensagensPadraoErro($acao, $posicao);
                }                
            }
        }

        return ['codigo' => $codigo, 'mensagem' => $respostaErro];       
    }
}