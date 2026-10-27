<?php

namespace services;

use utils\GeradorCodigoEmail;
use models\Usuarios;

class UsuariosService
{

    public function cadastrando(string $nome, string $email, string $senha, int $idade, ?string $codigoConvite) : array
    {

        $Usuarios = new Usuarios;

        $codigoEmail = GeradorCodigoEmail::gerar();

        $cadastrar = $Usuarios->cadastrar($nome, $email, $senha, $idade, $codigoEmail, $codigoConvite);

        $usuario['dados'] = $Usuarios->detalhes($cadastrar['usuario']);

        return array_merge($cadastrar, $usuario);
    }
}