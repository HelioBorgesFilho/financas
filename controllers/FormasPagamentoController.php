<?php

namespace controllers;

use controllers\Resposta;
use models\Usuarios;
use controllers\Validacao;
use services\FormasPagamentoService;
use models\FormaPagamento;

class FormasPagamentoController {

    public function cadastrarPagamento(array $data)
    {

        $Resposta = new Resposta;

        if (empty($data)) {
            return $Resposta->modeloRespostaErro(400,  'JSON mal formado.', 'validação');
        }

        $nome = $data['nome'] ?? null;
        $tipo = $data['tipo'] ?? null;
        $digitoCartao = $data['digito_cartao'] ?? null;

        $nomeValido = is_string($nome) && trim($nome) !== '';
        $tipoValido = is_string($tipo) && trim($tipo) !== '';

        $digitoValido = $digitoCartao === null || (
            is_string($digitoCartao) && (
                trim($digitoCartao) === '' ||
                preg_match('/^[0-9]{4}$/', trim($digitoCartao)) === 1
            )
        );

        if ($nomeValido && $tipoValido && $digitoValido) {

            $FormasPagamentoService = new FormasPagamentoService;
            $Usuarios = new Usuarios;

            $usuario = $Usuarios->detalhes($_SESSION['usuario_id']);

            $adicionar = $FormasPagamentoService->adicionarFormaPagamento($usuario, $data);

            if($adicionar['status']){

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
                401,
                'Preencha os campos obrigatórios e confira os tipos informados.',
                'validacao',
                $arrayCampos
            );

            if (!$digitoValido) {
                $mensagem['mensagem']['erro']['campos']['digito_cartao'] =
                    'Deve conter exatamente 4 dígitos numéricos.';
            }

            return $mensagem;
        }
    } 
    
    public function excluirPagamento($data){

        $FormaPagamento = new FormaPagamento;
        $Resposta = new Resposta;

        $usuario = $_SESSION['usuario_id'];

        $formas = $FormaPagamento->metodosPagamentos($usuario);

        if(!empty($formas)) {

            foreach($formas as $forma){

                if($forma['id'] == $data[0] && $forma['deletada'] == 0){

                    $excluir = $FormaPagamento->remover($data[0]);

                    if($excluir['status']){

                        return $Resposta->resposta(200, $excluir['mensagem']);
                    }

                    return $Resposta->modeloRespostaErro(500, 'Erro interno');
                }
            }

            return $Resposta->modeloRespostaErro(404, 'Forma não encontrada, já excluída ou não pertencente ao usuário.');
        }

        return $Resposta->modeloRespostaErro(200, 'Não possue formas de pagamento cadastradas.');
    }

    public function listarPagamentos(){

        $FormaPagamento = new FormaPagamento;
        $Resposta = new Resposta;

        $usuario = $_SESSION['usuario_id'];

        $formas = $FormaPagamento->metodosPagamentos($usuario);

        if(!empty($formas)) {

            $opcoesFormas = [];

            foreach ($formas as $posicao => $forma) {

                $opcoesFormas[$posicao] = ['id' => $forma['id'], 'nome' => $forma['nome'], 'tipo' => $forma['tipo']];

                if ($forma['digito_cartao'] != null) {
                    $opcoesFormas[$posicao]['digito_cartao'] = $forma['digito_cartao'];
                }
                if ($forma['apelido_cartao'] != null) {
                    $opcoesFormas[$posicao]['apelido_cartao'] = $forma['apelido_cartao'];
                }
            }

            return $Resposta->resposta(200, $opcoesFormas);
        }

        return $Resposta->modeloRespostaErro(200, []);
    }

    public function editarPagamentos(int $idForma, array $data){

        $Resposta = new Resposta;

        if (empty($data)) {
            return $Resposta->modeloRespostaErro(400,  'JSON mal formado.', 'validação');
        }

        $digitoCartao = $data['digito_cartao'] ?? null;

        $digitoValido = $digitoCartao === null || (
            is_string($digitoCartao) && (
                trim($digitoCartao) === '' ||
                preg_match('/^[0-9]{4}$/', trim($digitoCartao)) === 1
            )
        );

        if($digitoValido){

            $FormasPagamentoService = new FormasPagamentoService;

            $data['id'] = $idForma;

            $atualizar = $FormasPagamentoService->atualizarFormaPagamento($data);

            if ($atualizar['status']) {

                return $Resposta->resposta($atualizar['codigo'], $atualizar['mensagem']);
            }

            return $Resposta->modeloRespostaErro($atualizar['codigo'], $atualizar['mensagem']);

        } else {

            if (!$digitoValido) {

                $array['digito_cartao'] = '';

                return $Resposta->modeloRespostaErro(400, 'Um ou mais erros encontrados:', 'validação', $array);
            }
        }        
    }
}