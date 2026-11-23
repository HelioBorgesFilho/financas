<?php

namespace controllers;

class Validacao {

    public function camposObrigatorios(string $requisicao, array $dados): array{

        $campos = [
            'cadastro' => ['nome' => 'obrigatorio', 'email' => 'obrigatorio', 'senha' => 'obrigatorio'],
            'login' => ['nome' => 'obrigatorio', 'email' => 'obrigatorio', 'senha' => 'obrigatorio'],
            'cadastrarFormaPagamento' => ['nome' => 'obrigatorio', 'tipo' => 'obrigatorio'],
            'lancamento' => ['tipo' => 'obrigatorio', 'descricao' => 'obrigatorio', 'local' => 'obrigatorio', 'valor_centavos' => 'obrigatorio', 'forma_pagamento_id' => 'obrigatorio', 'data' => 'obrigatorio', 'categoria' => 'obrigatorio', 'em_nome_de_id' => 'obrigatorio', 'lancado_por_id' => 'obrigatorio'],
            'despesa' => ['tipo' => 'obrigatorio', 'descricao' => 'obrigatorio', 'valor_centavos' => 'obrigatorio', 'data' => 'obrigatorio', 'categoria' => 'obrigatorio', 'em_nome_de_id' => 'obrigatorio', 'lancado_por_id' => 'obrigatorio'],
        ];

        $campoSelecionado = [];

        foreach($campos[$requisicao] as $posicao => $campo){

            if(isset($dados[$posicao])){

                if(trim($dados[$posicao]) == null){

                    $campoSelecionado[$posicao] = '';
                }
            } else {

                if($campo == 'obrigatorio'){

                    $campoSelecionado[$posicao] = '';
                }
            }        
        }

        $validado = empty($campoSelecionado) ? true : false;

        return ['validado' => $validado, 'dados' => $campoSelecionado];
    }
}