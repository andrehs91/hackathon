<?php

spl_autoload_register(
    function (string $classe): void
    {
        $classe = str_replace('\\', DIRECTORY_SEPARATOR, $classe);
        $classe = str_replace('Hackathon', '', $classe);
        $classe = str_replace('Api', 'api', $classe);
        $classe = str_replace('Controller', 'controller', $classe);
        $classe = str_replace('Dao', 'dao', $classe);
        $classe = str_replace('Infrastructure', 'infrastructure', $classe);
        $classe = str_replace('Model', 'model', $classe);
        $caminho = str_replace('public', 'source', getcwd());
        $arquivo = $caminho . $classe . '.php';
        include_once $arquivo;
    }
);
