<?php

require_once __DIR__ . '/../config/autoload.php';

$migrationsPath = __DIR__ . '/../database/migrations';

use core\Env;

$envPath  = __DIR__ . '/../.env';
Env::load($envPath);

$files = glob($migrationsPath . '/*.php');

if (!$files) {
    echo "Nenhuma migration encontrada.\n";
    exit;
}

foreach ($files as $file) {

    // nome do arquivo sem extensão
    $className = pathinfo($file, PATHINFO_FILENAME);

    // namespace completo da classe
    $fqcn = "migrations\\{$className}";

    if (!class_exists($fqcn)) {
        echo "❌ Classe não encontrada: {$fqcn}\n";
        continue;
    }

    if (!method_exists($fqcn, 'up')) {
        echo "⚠ Migration sem método up(): {$fqcn}\n";
        continue;
    }

    $migration = new $fqcn();

    try {
        $migration->up();
        echo "✔ Executada: {$className}\n";
    } catch (Throwable $e) {
        echo "🔥 Erro em {$className}: {$e->getMessage()}\n";
        exit;
    }
}