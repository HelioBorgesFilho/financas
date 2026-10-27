<?php

namespace utils;

class Codigos
{

    public function codigo($cadastrar)
    {

        $errosMysql = [
            1062 => ['mensagem' => 'Valor duplicado em chave primária ou campo único.', 'http' => 409],
            1048 => ['mensagem' => 'A coluna não aceita NULL.', 'http' => 422],
            1364 => ['mensagem' => 'Campo obrigatório não informado e sem valor padrão.','http' => 422],
            1452 => ['mensagem' => 'O registro relacionado pela chave estrangeira não existe.','http' => 422],
            1406 => ['mensagem' => 'O texto ultrapassa o tamanho permitido.','http' => 422],
            1264 => ['mensagem' => 'Número fora do intervalo permitido pela coluna.','http' => 422],
            1366 => ['mensagem' => 'Valor incompatível com o tipo da coluna.','http' => 422],
            1292 => ['mensagem' => 'Conversão inválida de data ou valor.','http' => 422],
            1136 => ['mensagem' => 'A quantidade de valores não corresponde à quantidade de colunas.','http' => 500],
            1054 => ['mensagem' => 'A coluna informada não existe.','http' => 500],
            1146 => ['mensagem' => 'A tabela informada não existe.','http' => 500],
            1064 => ['mensagem' => 'Erro de sintaxe SQL.','http' => 500],
            1110 => ['mensagem' => 'A mesma coluna foi informada mais de uma vez.','http' => 500],
            1142 => ['mensagem' => 'Usuário do banco sem permissão para executar o comando.','http' => 500],
            3819 => ['mensagem' => 'O valor viola uma restrição CHECK.','http' => 422],
            1205 => ['mensagem' => 'Tempo de espera por bloqueio esgotado.','http' => 503],
            1213 => ['mensagem' => 'Conflito de bloqueios entre transações.','http' => 503],
            1114 => ['mensagem' => 'A tabela ou o armazenamento atingiu um limite.','http' => 503],
            1265 => ['mensagem' => 'Dado truncado: o valor enviado não é compatível com o formato ou limite da coluna.','http' => 422],

            1045 => ['mensagem' => 'Acesso negado na autenticação do banco.','http' => 500],
            1044 => ['mensagem' => 'Usuário do banco sem acesso ao banco solicitado.','http' => 500],
            1049 => ['mensagem' => 'O banco de dados informado não existe.','http' => 500],
            1040 => ['mensagem' => 'O servidor atingiu o limite de conexões.','http' => 503],
            1129 => ['mensagem' => 'Host bloqueado após muitos erros de conexão.','http' => 503],
            1130 => ['mensagem' => 'Host não autorizado a conectar ao MySQL.','http' => 500],
            1203 => ['mensagem' => 'O usuário do banco atingiu o limite de conexões simultâneas.','http' => 503],
            1820 => ['mensagem' => 'A senha do banco precisa ser alterada.','http' => 500],
            2002 => ['mensagem' => 'Não foi possível estabelecer a conexão com o MySQL.','http' => 503],
            2003 => ['mensagem' => 'Não foi possível conectar ao host e à porta do MySQL.','http' => 503],
            2005 => ['mensagem' => 'Nome do host desconhecido ou falha de DNS.','http' => 503],
            2006 => ['mensagem' => 'A conexão com o servidor foi encerrada.','http' => 503],
            2012 => ['mensagem' => 'Falha na negociação inicial da conexão.','http' => 503],
            2013 => ['mensagem' => 'A conexão foi perdida durante a consulta.','http' => 503],
            2026 => ['mensagem' => 'Erro na conexão SSL/TLS.','http' => 500],
        ];

        foreach($errosMysql as $posicao => $erro){

            if($cadastrar[1] == $posicao){

                return $erro;
            }

        }
        return ['Codigo erro não encontrado'];
    }
}