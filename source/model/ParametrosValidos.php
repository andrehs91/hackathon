<?php

namespace Hackathon\Model;

use InvalidArgumentException;

class ParametrosValidos
{
    
    private array $parametrosValidos;
    
    public function definirParametro(string $nome, string $tipo, bool $obrigatorio): void
    {
        if (!in_array($tipo, ['bool', 'email', 'float', 'int', 'string', 'url'])) {
            throw new InvalidArgumentException('O parâmetro "tipo" aceita apenas os valores "bool", "email", "float", "int", "string" e "url".');
        }
        $this->parametrosValidos[] = [
            'nome' => $nome,
            'tipo' => $tipo,
            'obrigatorio' => $obrigatorio
        ];
    }
    
    public function obterParametros(): array
    { return $this->parametrosValidos; }
    
}
