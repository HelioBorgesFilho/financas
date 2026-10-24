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

    private function resposta($code, $mensagem)
    {
        return ['code' => $code, 'status' => ['mensagem' => $mensagem]];
    }

    public function camposObrigatorios($requisicao, $dados): array{

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

                    $arrayRetorno[] = $posicao;
                }
            }
        }

        return $arrayRetorno;
    }

    public function mensagensPadraoErro($nomeMensagem){

        $mensagens = [
            'nome' => 'Informe um nome com pelo menos 2 letras.',
            'email' => 'Informe o e-mail.',
            'senha' => 'Use pelo menos 8 caracteres.', 
            'idade' => 'Informe sua idade.',
        ];

        $mensagem = 'Campo não encontrado, contato dezenvolvedor.';

        foreach($mensagens as $posicao => $msg){

            if($mensagens[$posicao]){

                if($posicao == $nomeMensagem){

                    $mensagem = $msg;
                }
            }else{

                $mensagem = 'Campo não encontrado, contato dezenvolvedor.';
            }
        }
        return $mensagem;
    }

    public function modeloRespostaErro($codigo, $mensagem, $campos = null){

        $respostaErro['erro'] = ['codigo' => $codigo, 'mensagem' => $mensagem];

        if($campos){

            foreach($campos as $campo){                

                $camposSelecionados[$campo] = $this->mensagensPadraoErro($campo);
            }
        }
        if(isset($camposSelecionados)){

            $respostaErro['erro']['campos'] = $camposSelecionados;
        }
        return $respostaErro;       
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

                $email = trim($data['email']);
                $senha = trim($data['senha']);

                if ($data['email'] != null && $data['senha'] != null) {

                    $JwtService = new JwtService;

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

                        $resposta = $this->resposta(202, $mensagem);
                    } else {

                        $erro = explode(' ', $cadastrar["mensagem"][2]);

                        $Utils = new Codigos;
                        $retorno = $Utils->codigo($cadastrar);

                        $codigo = isset($erro[5]) == 'email' ? 'email_em_uso' : 'erro geral';
                        $mensagem = $codigo == 'email_em_uso' ? 'Já existe uma conta com esse e-mail.' : $Utils->codigo($cadastrar);

                        $modeloRespostaErro = $this->modeloRespostaErro($codigo, $mensagem);
                        $resposta = $this->resposta($retorno["http"], $modeloRespostaErro);
                    }
                } else {

                    if ($nome == null && $email == null && $senha == null && !is_int($idade)) {

                        $resposta = $this->resposta(400, ['erro' => 'JSON mal formado!']);

                    } else {

                        $dados = ['nome' => $nome, 'email' => $email, 'senha' => $senha, 'idade' => $idade];
                        $arrayCampos = $this->camposObrigatorios('cadastro', $dados);

                        $modeloRespostaErro = $this->modeloRespostaErro('validacao', 'Preencha os campos obrigatórios destacados.', $arrayCampos);
                        
                        $resposta = $this->resposta(422, $modeloRespostaErro);
                    }  
                }

                break;

            default:

                $resposta = $this->resposta(404, 'A requisição não foi encontrada!');
        }

        return $resposta;
    }
}
