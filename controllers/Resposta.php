<?php

namespace controllers;

class Resposta {

    public function resposta($codigo, $mensagem): array
    {
        return ['codigo' => $codigo, 'mensagem' => $mensagem];
    }

    public function mensagensPadraoErro($nomeMensagem): string{

        $mensagens = [
            'nome' => 'Informe um nome com pelo menos 2 letras.',
            'email' => 'Informe o e-mail.',
            'senha' => 'Use pelo menos 8 caracteres.', 
            'idade' => 'Informe sua idade.',
            'tipo' => 'Escolha o tipo da forma de pagamento.',
            'digito_cartao' => 'Deve conter exatamente 4 dígitos numéricos.'
        ];

        $mensagem = '';

        foreach($mensagens as $posicao => $msg){

            if(isset($mensagens[$posicao])){

                if($posicao == $nomeMensagem){

                    $mensagem = $msg;
                }
            }else{

                $mensagem = 'Campo não encontrado, contato desenvolvedor.';
            }
        }
        return $mensagem;
    }

    public function modeloRespostaErro(int $codigo, $mensagem, ?string $motivo = null, $campos = null): array {

        $respostaErro = ['erro' => ['mensagem' => $mensagem]];

        if($motivo == null){

            $respostaErro = ['erro' => ['motivo' => $motivo,'mensagem' => $mensagem]];
        }       

        if($campos){

            foreach($campos as $posicao => $campo){  
                
                if($campo == null){

                    $respostaErro["erro"]['campos'][$posicao] = $this->mensagensPadraoErro($posicao);
                }                
            }
        }

        return ['codigo' => $codigo, 'mensagem' => $respostaErro];       
    }
}