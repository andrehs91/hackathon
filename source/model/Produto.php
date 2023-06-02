<?php

namespace Hackathon\Model;

class Produto
{
    
    public function __construct(
        private int     $codigoProduto,
        private string  $nomeProduto,
        private float   $percentualTaxaJuros,
        private int     $numeroMinimoMeses,
        private ?int    $numeroMaximoMeses = null,
        private float   $valorMinimo,
        private ?float  $valorMaximo = null
    ) {}
    
    public function obterCodigoProduto(): int
    { return $this->codigoProduto; }
    
    public function obterNomeProduto(): string
    { return $this->nomeProduto; }
    
    public function obterPercentualTaxaJuros(): float
    { return $this->percentualTaxaJuros; }
    
    public function obterNumeroMinimoMeses(): int
    { return $this->numeroMinimoMeses; }
    
    public function obterNumeroMaximoMeses(): ?int
    { return $this->numeroMaximoMeses; }
    
    public function obterValorMinimo(): float
    { return $this->valorMinimo; }
    
    public function obterValorMaximo(): ?float
    { return $this->valorMaximo; }
    
}
