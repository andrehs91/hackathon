<?php

namespace Hackathon\Infrastructure;

use PDO;

class SQLServer
{
    
    public static function conectar(): PDO
    {
        $dsn = 'sqlsrv:Server=dbhackathon.database.windows.net,1433;Database=hack';
        $username = 'hack';
        $password = 'Password23';
        $conexao = new PDO($dsn, $username, $password);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conexao;
    }
    
}
