<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';

    $migrate = explode('\\', $class, 2);

    if($migrate[0] == 'migrations'){

        $class = "database\\$class";
    }

    $classPath = str_replace('\\', '/', $class) . '.php';
    
    $file = $baseDir . $classPath;

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class $class not found in $file");
    }
});