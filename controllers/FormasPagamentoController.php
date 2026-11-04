<?php

namespace controllers;

use controllers\Resposta;
use services\JwtService;
use models\Usuarios;
use controllers\Validacao;
use services\FormasPagamentoService;

class FormasPagamentoController {

    public function cadastrarPagamento(array $data, string $acesso)
    {

        $Resposta = new Resposta;

        if (empty($data)) {

            $mensagem = $Resposta->modeloRespostaErro('sintaxe', 
            'Requisição Malformada.');
            return $Resposta->resposta(400, $mensagem);
        }

        $nome = $data['nome'] ?? null;
        $tipo = $data['tipo'] ?? null;
        $digitoCartao = $data['digito_cartao'] ?? null;

        $nomeValido = is_string($nome) && trim($nome) !== '';
        $tipoValido = is_string($tipo) && trim($tipo) !== '';

        $digitoValido = $digitoCartao === null || (
            is_string($digitoCartao)
            && preg_match('/^[0-9]{4}$/', trim($digitoCartao)) === 1
        );

        if ($nomeValido && $tipoValido && $digitoValido) {

            $FormasPagamentoService = new FormasPagamentoService;
            $Usuarios = new Usuarios;

            $usuario = $Usuarios->detalhes($_SESSION['usuario_id']);

            $adicionar = $FormasPagamentoService->adicionarFormaPagamento($usuario, $data);

            if($adicionar['status']){

                if($acesso == 'Seu token expirou.'){

                    $JwtService = new JwtService;        
                    
                    $novoToken = $JwtService->generateToken($usuario);

                    $adicionar['mensagem'] = array_merge($adicionar['mensagem'], ['token' => $novoToken]);
                }

                return $Resposta->resposta($adicionar["codigo"], $adicionar["mensagem"]);
            }

            return $Resposta->modeloRespostaErro($adicionar["codigo"], $adicionar["mensagem"]);

        } else {

            $Validacao = new Validacao;

            $dados = [
                'nome' => is_string($nome) ? trim($nome) : null,
                'tipo' => is_string($tipo) ? trim($tipo) : null,
            ];

            $arrayCampos = $Validacao->camposObrigatorios('formaPagamento', $dados);

            $mensagem = $Resposta->modeloRespostaErro(
                'validacao',
                'Preencha os campos obrigatórios e confira os tipos informados.',
                $arrayCampos
            );

            if (!$digitoValido) {
                $mensagem['erro']['campos']['digito_cartao'] =
                    'Deve conter exatamente 4 dígitos numéricos.';
            }

            return $Resposta->resposta(422, $mensagem);
        }
    }    
}