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

        if (empty($data)) {
            return $Resposta->modeloRespostaErro(400,  'JSON mal formado.', 'validação');
        }

        $email = isset($data['email']) && trim($data['email']) != '' ? trim($data['email']) : null;
        $senha = isset($data['senha']) && trim($data['senha']) != '' ? trim($data['senha']) : null;

        if ($email != null && $senha != null) {

            $Usuarios = new Usuarios;

            $usuario = $Usuarios->usuario($email);

            if ($email == $usuario['email'] && $senha == $usuario['senha']) {

                $AuthService = new AuthService;

                $logando = $AuthService->logando($usuario);
                return $Resposta->resposta(200, $logando['mensagem']);

            } else {

                return $Resposta->modeloRespostaErro(401,'E-mail ou senha incorretos.','credenciais_invalidas');
            }
        } else {

            $campos = ['email' => $email, 'senha' => $senha];

            return $Resposta->modeloRespostaErro(422, 'Preencha os campos obrigatórios destacados.', 'validação', $campos);
        }
    }

    public function cadastrar(array $data): array
    {

        $Resposta = new Resposta;

        if (empty($data)) {
            return $Resposta->modeloRespostaErro(400,  'JSON mal formado.', 'validação');
        }

        $nome = isset($data['nome']) && trim($data['nome']) != '' ? trim($data['nome']) : null;
        $email = isset($data['email']) && trim($data['email']) != '' ? trim($data['email']) : null;
        $senha = isset($data['senha']) && trim($data['senha']) != '' ? trim($data['senha']) : null;
        $codigoConvite = isset($data['convite_codigo']) && trim($data['convite_codigo']) != '' ? trim($data['convite_codigo']) : null;

        $idade = $data['idade'] ?? null;

        $idadeValida = $idade === null || (
            (is_string($idade) || is_int($idade)) && (
                trim((string) $idade) === '' ||
                preg_match('/^[0-9]{2}$/', trim((string) $idade)) === 1
            )
        );

        if($idadeValida){

            if ($nome == null || $email == null || $senha == null) {
                $Validacao = new Validacao;
    
                $dados = [
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => $senha,
                ];
    
                $arrayCampos = $Validacao->camposObrigatorios('cadastro', $dados);
    
                return $Resposta->modeloRespostaErro(422,
                    'Preencha os campos obrigatórios e confira os tipos informados.', 'validacao',
                    $arrayCampos
                );
            }
    
            $UsuariosService = new UsuariosService;
            $cadastrando = $UsuariosService->cadastrando($nome, $email, $senha, $idade, $codigoConvite);
    
            if($cadastrando['dados']){
    
                $mensagem = array_merge(['status' => $cadastrando["mensagem"]], $cadastrando["dados"]);
    
                return $Resposta->resposta(200, $mensagem);
            }else {
    
                $erro = explode(' ', $cadastrando["mensagem"][2]);
                $Utils = new Codigos;
                $retorno = $Utils->codigo($cadastrando["mensagem"]);
                $codigoErro = isset($erro[5]) == 'email' ? 'email_em_uso' : 'erro geral';
                $mensagemErro = $codigoErro == 'email_em_uso' ? 'Já existe uma conta com esse e-mail.' : $Utils->codigo($cadastrando);
                return $Resposta->modeloRespostaErro($retorno["http"], $mensagemErro, 'email em uso');
            }
        }  

        $array['idade'] = '';

        return $Resposta->modeloRespostaErro(401, 'O caampo deve conter entre 1 à 3 dígitos numéricos.', 'validacao', $array);        
    }
}