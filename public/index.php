<?php

require '../autoload.php';
require '../vendor/autoload.php';

use Hackathon\Controller\RespostaController;

$caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$caminho = strtolower($caminho);
if (substr($caminho, 0, 1) == '/') $caminho = substr($caminho, 1);
if (substr($caminho, -1) == '/') $caminho = substr($caminho, 0, -1);

$partesCaminho = explode('/', $caminho);
if ($partesCaminho[0] !== 'api') {
    $respostaController = new RespostaController('Endpoint não encontrado.', 404);
    $respostaController->enviar();
}

$classe = 'Hackathon';
foreach ($partesCaminho as $parteCaminho) {
    $classe .= '\\' . ucfirst($parteCaminho);
}
if (!class_exists($classe)) {
    $respostaController = new RespostaController('Recurso não encontrado.', 404);
    $respostaController->enviar();
}

$recurso = new $classe;
try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':     $recurso->get();     break;
        case 'POST':    $recurso->post();    break;
        case 'PUT':     $recurso->put();     break;
        case 'PATCH':   $recurso->patch();   break;
        case 'DELETE':  $recurso->delete();  break;
        case 'HEAD':    $recurso->head();    break;
        case 'OPTIONS': $recurso->options(); break;
    }
} catch (\DomainException $exception) {
    $mensagem = $exception->getMessage();
    if ($mensagem == 'Método HTTP não implementado.') {
        $respostaController = new RespostaController($mensagem, 501);
        $respostaController->enviar();
    }
}
