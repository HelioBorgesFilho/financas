<?php

namespace controllers;

use middleware\AuthMiddleware;
use utils\GeradorCodigoEmail;
use models\Usuarios;
use controllers\Validacao;
use utils\Codigos;

class Controller
{

    private function resposta($code, $mensagem)
    {
        return ['code' => $code, 'status' => ['mensagem' => $mensagem]];
    }

    public function camposObrigatorios($requisicao, $dados): array{

        $campos = [
            'cadastro' => ['nome' => 'obrigatorio', 'email' => 'obrigatorio', 'senha' => 'obrigatorio'],
        ];

        $array = [];

        foreach($campos as $posicao => $campo){

            if($posicao == $requisicao){

                $array = $campo;
            }
        }

        $arrayRetorno = [];

        foreach($dados as $posicao => $dado){

            if($array[$posicao] == 'obrigatorio'){

                if($dado == null){

                    $arrayRetorno[] = $posicao;
                }
            }
        }

        return $arrayRetorno;
    }

    public function checkingRoutes($route, $numberOfRoute)
    {
        if ($numberOfRoute < 1 || $numberOfRoute > 10) {
            return false;
        }
        return true;
    }

    public function mainController($option, $data = [])
    {
        $AuthMiddleware = new AuthMiddleware;
        $Resposta = new Resposta;

        switch ($option) {

            case 'familia':

                $return = $AuthMiddleware->handle();

                if ($return['status']) {

                    $resposta = $Resposta->resposta(200, $return['mensagem']);
                } else {

                    $resposta = $Resposta->resposta(401, $return['mensagem']);
                }

                break;

            case 'login':

                $AuthService = new AuthService;

                    $user['id'] = 1;
                    $user['email'] = 'lucas.rios.se@gmail.com';

                    $token = $JwtService->generateToken($user);

                    $resposta = $this->resposta(200, $token);

                } else {

                    $campos = $dados = ['email' => $email, 'senha' => $senha];

                    $modeloRespostaErro = $this->modeloRespostaErro('validação', 'Preencha os campos obrigatórios destacados.', $campos);

                    $resposta = $this->resposta(422, ['erro' => $modeloRespostaErro]);
                }

                break;

            case 'cadastro':

                $Usuarios = new Usuarios;

                $nome = $data['nome'] ?? null;
                $email = $data['email'] ?? null;
                $senha = $data['senha'] ?? null;
                $idade = $data['idade'] ?? null;
                $codigoConvite = isset($data["convite_codigo"]) ? $data['codigoConvite'] : null;

                if ($nome != null && $email != null && $senha != null && is_int($idade)) {

                    $codigoEmail = GeradorCodigoEmail::gerar();

                    $cadastrar = $Usuarios->cadastrar($nome, $email, $senha, $idade, $codigoEmail, $codigoConvite);

                    if ($cadastrar['status']) {

                        $usuario['dados'] = $Usuarios->detalhes($cadastrar['usuario']);

                        $mensagem = array_merge(['status' => $cadastrar['mensagem']], $usuario);

                        $resposta = $Resposta->resposta(202, $mensagem);
                    } else {

                        $erro = explode(' ', $cadastrar["mensagem"][2]);

                        $Utils = new Codigos;
                        $retorno = $Utils->codigo($cadastrar);

                        $codigo = isset($erro[5]) == 'email' ? 'email_em_uso' : 'erro geral';
                        $mensagem = $codigo == 'email_em_uso' ? 'Já existe uma conta com esse e-mail.' : $Utils->codigo($cadastrar);

                        $modeloRespostaErro = $Resposta->modeloRespostaErro($codigo, $mensagem);
                        $resposta = $Resposta->resposta($retorno["http"], $modeloRespostaErro);
                    }
                } else {

                    if ($nome == null && $email == null && $senha == null && !is_int($idade)) {

                        $resposta = $Resposta->resposta(400, ['erro' => 'JSON mal formado!']);

                    } else {

                        $Validacao = new Validacao;

                        $dados = ['nome' => $nome, 'email' => $email, 'senha' => $senha, 'idade' => $idade];
                        $arrayCampos = $Validacao->camposObrigatorios('cadastro', $dados);

                        $modeloRespostaErro = $Resposta->modeloRespostaErro('validacao', 'Preencha os campos obrigatórios destacados.', $arrayCampos);
                        
                        $resposta = $Resposta->resposta(422, $modeloRespostaErro);
                    }  
                }

                break;

            case 'renovar':

                $sessaoAberta = AuthService::sessaoAberta();

                $resposta = $this->resposta($sessaoAberta['code'], $sessaoAberta['mensagem']);

                break;

            default:

                $resposta = $Resposta->resposta(404, 'A requisição não foi encontrada!');
        }

        return $resposta;
    }
}