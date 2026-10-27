<?php

namespace controllers;

class Validacao {

    public function camposObrigatorios(string $requisicao, array $dados): array{

        $campos = [
            'cadastro' => ['nome' => 'obrigatorio', 'email' => 'obrigatorio', 'senha' => 'obrigatorio'],
            'login' => ['nome' => 'obrigatorio', 'email' => 'obrigatorio', 'senha' => 'obrigatorio'],
        ];

        $campoSelecionado = [];

        foreach($campos as $posicao => $campo){

            if($campos[$posicao]){

                if($posicao == $requisicao){

                    $campoSelecionado = $campo;
                }
            }else{

                $campoSelecionado = $campo;
            }            
        }

        $arrayRetorno = [];

        foreach($dados as $posicao => $dado){

            if($campoSelecionado[$posicao] == 'obrigatorio'){

                if($dado == null){

                    $arrayRetorno[$posicao] = $dado;
                }
            }
        }

        return $arrayRetorno;
    }
}