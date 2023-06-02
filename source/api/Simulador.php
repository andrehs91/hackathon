<?php

namespace Hackathon\Api;

use Hackathon\Controller\EventHubController;
use Hackathon\Controller\RecursoController;
use Hackathon\Controller\RequisicaoController;
use Hackathon\Controller\RespostaController;
use Hackathon\Dao\ProdutoDao;
use Hackathon\Infrastructure\EventHub;
use Hackathon\Infrastructure\SQLServer;
use Hackathon\Model\ParametrosValidos;

class Simulador extends RecursoController
{
    
    public function post(): void
    {
        $parametrosValidos = new ParametrosValidos();
        $parametrosValidos->definirParametro(nome: 'valorDesejado', tipo: 'float', obrigatorio: true);
        $parametrosValidos->definirParametro(nome: 'prazo', tipo: 'int', obrigatorio: true);
        
        $requisicaoController = new RequisicaoController($parametrosValidos);
        try {
            $requisicaoController->validar();
        } catch (\DomainException $exception) {
            $respostaController = new RespostaController($exception->getMessage(), 400);
            $respostaController->enviar(finalizarExecucao: false);
        }
        $parametros = $requisicaoController->obterParametros();
        
        $pdo = SQLServer::conectar();
        
        $produtoDao = new ProdutoDao($pdo);
        $produto = $produtoDao->buscarPorParametros($parametros['valorDesejado'], $parametros['prazo']);
        if ($produto === null) {
            $respostaController = new RespostaController('Não há produtos disponíveis para os parâmetros informados.', 400);
            $respostaController->enviar();
        }
        
        $simulador = new \Hackathon\Model\Simulador($produto);
        $simulacao = $simulador->simular($parametros['valorDesejado'], $parametros['prazo']);
        
        $respostaController = new RespostaController($simulacao);
        $respostaController->enviar(finalizarExecucao: false);
        
        $eventHub = new EventHub('eventhack', 'simulacoes', 'hack', 'HeHeVaVqyVkntO2FnjQcs2Ilh/4MUDo4y+AEhKp8z+g=');
        $eventHubController = new EventHubController($eventHub);
        $eventHubController->send($simulacao);
    }
    
}
