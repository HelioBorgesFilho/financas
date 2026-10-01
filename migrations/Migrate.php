<?php

require_once __DIR__ . '/../config/autoload.php';

$migrationsPath = __DIR__ . '/../database/migrations';

use core\Env;

$envPath  = __DIR__ . '/../.env';
Env::load($envPath);

use models\Migrations;

$migrations = new Migrations;

$files = glob($migrationsPath . '/*.sql');

if (!$files) {
    echo "Nenhuma migration encontrada.\n";
    exit;
}

foreach ($files as $key => $file) {

    $sql = file_get_contents($file);

    $migrate = $migrations->migrate($sql);  
    
    if($migrate){
        echo 'Criada ou alterada'.PHP_EOL;
    }else{
        echo 'Erro ao criar ou alteradar'.PHP_EOL;
    }
}