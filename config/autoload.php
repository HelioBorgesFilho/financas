<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';

    $classPath = str_replace('\\', '/', $class) . '.php';
    
    $file = $baseDir . $classPath;

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class $class not found in $file");
    }
});