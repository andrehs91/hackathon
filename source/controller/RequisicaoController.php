<?php

namespace Hackathon\Controller;

use Hackathon\Model\ParametrosValidos;
use DomainException;

class RequisicaoController
{
    
    private array $parametros = [];
    private static array $filtro = [
        'bool' => FILTER_VALIDATE_BOOL,
        'email' => FILTER_VALIDATE_EMAIL,
        'float' => FILTER_VALIDATE_FLOAT,
        'int' => FILTER_VALIDATE_INT,
        'string' => FILTER_SANITIZE_SPECIAL_CHARS,
        'url' => FILTER_VALIDATE_URL
    ];
    // Conforme tipos de dados definidos na tabela do banco de dados
    private static array $intervalos = [
        'float' => ["min_range" => -9999999999999999.99, "max_range" => 9999999999999999.99],
        'int' => ["min_range" => -32768, "max_range" => 32767]
    ];
    
    public function __construct(
        private ParametrosValidos $parametrosValidos
    ) {}
    
    public function validar(): void
    {
        $parametros = json_decode(file_get_contents('php://input'), true);
        if ($parametros === null) {
            throw new DomainException("O formato do corpo da requisição é inválido.");
        }
        
        foreach ($this->parametrosValidos->obterParametros() as $parametro) {
            if (!array_key_exists($parametro['nome'], $parametros)) {
                if ($parametro['obrigatorio'] === false) {
                    $this->parametros[$parametro['nome']] = null;
                    continue;
                }
                throw new DomainException("O parâmetro '{$parametro['nome']}' não foi informado.");
            }
            
            $opcoes = [];
            if ($parametro['tipo'] === 'bool') $opcoes = [FILTER_NULL_ON_FAILURE];
            if ($parametro['tipo'] === 'float') $opcoes = ["options" => self::$intervalos[$parametro['tipo']]];
            if ($parametro['tipo'] === 'int') $opcoes = ["options" => self::$intervalos[$parametro['tipo']]];
            
            $parametroValido = filter_var($parametros[$parametro['nome']], self::$filtro[$parametro['tipo']], $opcoes);
            if ($parametro['tipo'] !== 'bool' && $parametroValido === false) $parametroValido = null;
            if ($parametroValido === null && $parametro['obrigatorio'] === true) {
                throw new DomainException("O valor fornecido para o parâmetro '{$parametro['nome']}' é inválido.");
            }
            $this->parametros[$parametro['nome']] = $parametroValido;
        }
    }
    
    public function obterParametros(): array
    { return $this->parametros; }
    
}
