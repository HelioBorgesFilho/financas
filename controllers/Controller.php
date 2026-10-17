<?php

namespace controllers;

use middleware\AuthMiddleware;
use services\JwtService;
use utils\GeradorCodigoEmail;
use models\Usuarios;
use utils;
use utils\Codigos;

class Controller
{

    private function errorResposta($code, $mensagem)
    {
        return ['code' => $code, 'status' => ['mensagem' => $mensagem]];
    }

    public function resposta($code, $mensagem) // teste, remover depois
    {
        return ['code' => $code, 'status' => ['mensagem' => $mensagem]];
    }

    public function checkingRoutes($route, $numberOfRoute)
    {


        if ($numberOfRoute < 1 || $numberOfRoute > 10) {
            return false;
        }
        return true;
    }

    public function erro($codigo, $mensagem, $campos){
        
    }

    public function mainController($option, $data = [])
    {
        $AuthMiddleware = new AuthMiddleware;

        switch ($option) {

            case 'familia':

                $return = $AuthMiddleware->handle();

                if ($return['status']) {

                    $resposta = $this->resposta(200, $return['mensagem']);
                } else {

                    $resposta = $this->resposta(401, $return['mensagem']);
                }

                break;

            case 'login':

                if ($data['login'] != null && $data['password'] != null) {

                    $JwtService = new JwtService;

                    $return = $JwtService->generateToken($data);

                    $resposta = $this->resposta(200, $return);
                } elseif ($data['login'] == null && $data['password'] == null) {

                    $resposta = $this->resposta(401, 'Login e senha incorretas!');
                } elseif ($data['login'] == null) {

                    $resposta = $this->resposta(401, 'Login incorreto!');
                } elseif ($data['password'] == null) {

                    $resposta = $this->resposta(401, 'Senha incorreta!');
                } else {

                    $resposta = $this->errorResposta(404, 'A requisição não foi encontrada!');
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

                    $cadastrar = $Usuarios->novoRegistro($nome, $email, $senha, $idade, $codigoEmail, $codigoConvite);

                    if ($cadastrar['status']) {

                        $usuario['dados'] = $Usuarios->usuario($cadastrar['usuario']);

                        $mensagem = array_merge(['status' => $cadastrar['mensagem']], $usuario);

                        $resposta = $this->resposta(201, $mensagem);
                    } else {

                        $erro = explode(' ', $cadastrar["mensagem"][2]);

                        if(isset($erro[5]) == 'email'){

                            $resposta = $this->resposta(201, "Já existe uma conta com esse e-mail!");

                        } else {

                            $Utils = new Codigos;

                            $retorno = $Utils->codigo($cadastrar);
                            $resposta = $this->resposta($retorno["http"], $retorno["mensagem"]);
                        }
                    }
                } else {

                    if ($nome == null && $email == null && $senha == null && !is_int($idade)) {

                        $resposta = $this->resposta(201, 'JSON mal formado!');                     
                    } else {

                        $mensagem = '';
                        $conteErro = 0;

                        if ($nome == null) {

                            $mensagem .= 'nome';
                            $conteErro++;
                        }
                        if ($email == null) {

                            $mensagem .= $conteErro > 0 ? ', email' : 'email';
                            $conteErro++;
                        }
                        if ($senha == null) {

                            $mensagem .= $conteErro > 0 ? ', senha' : 'senha';
                            $conteErro++;
                        }
                        if (!is_int($idade)) {

                            $mensagem .= $conteErro > 0 ? ', idade' : 'idade';
                            $conteErro++;
                        }

                        $mensagemRetorno = 'O campo ';
                        $mensagemFinal = ' não pode estar vazio!';

                        if ($conteErro > 1) {

                            $mensagemRetorno = 'Os campos ';
                            $mensagemFinal = ' não podem estar vazios!';
                        }

                        $mensagemRetorno .= $mensagem . $mensagemFinal;

                        $resposta = $this->resposta(401, $mensagemRetorno);
                    }  
                }

                break;

            default:

                $resposta = $this->errorResposta(404, 'A requisição não foi encontrada!');
        }

        return $resposta;
    }
}
