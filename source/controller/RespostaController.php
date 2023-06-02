<?php

namespace Hackathon\Controller;

class RespostaController
{
    
    public function __construct(
        private array|string $conteudo,
        private ?int $codigo = null
    ) {}
    
    public function enviar($finalizarExecucao = true): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
        if (is_string($this->conteudo) && $this->codigo !== null) {
            http_response_code($this->codigo);
            echo json_encode([
                'Codigo' => $this->codigo,
                'Mensagem' => $this->conteudo
            ]);
        } else {
            echo json_encode($this->conteudo);
        }
        if ($finalizarExecucao) exit;
    }
    
}
