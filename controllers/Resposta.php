<?php

namespace controllers;

class Resposta {

    public function resposta($code, $mensagem): array
    {
        return ['code' => $code, 'mensagem' => $mensagem];
    }

    public function mensagensPadraoErro($nomeMensagem): string{

        $mensagens = [
            'nome' => 'Informe um nome com pelo menos 2 letras.',
            'email' => 'Informe o e-mail.',
            'senha' => 'Use pelo menos 8 caracteres.', 
            'idade' => 'Informe sua idade.',
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

    public function modeloRespostaErro($codigo, $mensagem, $campos = null): array {

        $respostaErro['erro'] = ['codigo' => $codigo, 'mensagem' => $mensagem];

        if($campos){

            foreach($campos as $posicao => $campo){  
                
                if($campo == null){

                    $camposSelecionados[$posicao] = $this->mensagensPadraoErro($posicao);
                }                
            }
        }
        if(isset($camposSelecionados)){

            $respostaErro['erro']['campos'] = $camposSelecionados;
        }
        return $respostaErro;       
    }
}