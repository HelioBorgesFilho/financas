<?php

namespace controllers;

use controllers\Resposta;
use models\Usuarios;
use services\AuthService;
use controllers\Validacao;
use services\UsuariosService;
use utils\Codigos;

class UsuariosController
{

    public function validarLog(array $data)
    {

        $Resposta = new Resposta;

        $email = trim($data['email']);
        $senha = trim($data['senha']);

        if ($data['email'] != null && $data['senha'] != null) {

            $Usuarios = new Usuarios;

            $usuario = $Usuarios->usuario($email);

            if ($email == $usuario['email'] && $senha == $usuario['senha']) {

                $AuthService = new AuthService;

                $logando = $AuthService->logando($usuario);
                $codigo = 200;
                $mensagem = $logando['mensagem'];
            } else {

                $mensagem = $Resposta->modeloRespostaErro('credenciais_invalidas', 'E-mail ou senha incorretos.');
                $codigo = 401;
            }
        } else {

            if ($data['email'] == null && $data['senha'] == null) {

                $mensagem = $Resposta->modeloRespostaErro('validação', 'JSON mal formado!');
                $codigo = 400;
            } else {

                $campos = ['email' => $email, 'senha' => $senha];

                $mensagem = $Resposta->modeloRespostaErro('validação', 'Preencha os campos obrigatórios destacados.', $campos);
                $codigo = 422;
            }
        }

        $resposta = $Resposta->resposta($codigo, $mensagem);

        return $resposta;
    }

    public function cadastrar(array $data): array
    {

        $Resposta = new Resposta;

        if (empty($data)) {
            return $Resposta->resposta(400, [
                'error' => 'Nenhum dado informado.'
            ]);
        }

        $nome = $data['nome'] ?? null;
        $email = $data['email'] ?? null;
        $senha = $data['senha'] ?? null;
        $idade = $data['idade'] ?? null;
        $codigoConvite = $data['convite_codigo'] ?? null;

        if ($nome == null || $email == null || $senha == null || !is_int($idade)) {
            $Validacao = new Validacao;

            $dados = [
                'nome' => $nome,
                'email' => $email,
                'senha' => $senha,
                'idade' => $idade
            ];

            $arrayCampos = $Validacao->camposObrigatorios('cadastro', $dados);

            $mensagem = $Resposta->modeloRespostaErro(
                'validacao',
                'Preencha os campos obrigatórios e confira os tipos informados.',
                $arrayCampos
            );

            return $Resposta->resposta(422, $mensagem);
        }

        $UsuariosService = new UsuariosService;
        $cadastrando = $UsuariosService->cadastrando($nome, $email, $senha, $idade, $codigoConvite);

        if($cadastrando['dados']){

            $codigo = 200;
            $mensagem = array_merge(['status' => $cadastrando["mensagem"]], $cadastrando["dados"]);
        }else {

            $erro = explode(' ', $cadastrando["mensagem"][2]);
            $Utils = new Codigos;
            $retorno = $Utils->codigo($cadastrando["mensagem"]);
            $codigoErro = isset($erro[5]) == 'email' ? 'email_em_uso' : 'erro geral';
            $mensagemErro = $codigoErro == 'email_em_uso' ? 'Já existe uma conta com esse e-mail.' : $Utils->codigo($cadastrando);
            $codigo = $retorno["http"];
            $mensagem = $Resposta->modeloRespostaErro($codigo, $mensagemErro);
        }

        return $Resposta->resposta($codigo, $mensagem);
    }
}