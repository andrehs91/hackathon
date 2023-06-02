<?php

namespace Hackathon\Model;

use Hackathon\Model\Produto;

class Simulador
{
    
    public function __construct(
        private Produto $produto
    ) {}
    
    private function calcularParcelasPrice(float $valor, int $prazo): array
    {
        if ($prazo == 0) return [];
        $saldoDevedor = round($valor, 2);
        $taxaJuros = $this->produto->obterPercentualTaxaJuros();
        $a = $saldoDevedor * $taxaJuros * pow((1 + $taxaJuros), $prazo);
        $b = pow((1 + $taxaJuros), $prazo) - 1;
        $valorPrestacao = round($a / $b, 2);
        $parcelas = [];
        for ($numero = 1; $numero <= $prazo; $numero++) {
            $valorJuros = round($saldoDevedor * $taxaJuros, 2);
            $valorAmortizacao = round($valorPrestacao - $valorJuros, 2);
            $parcelas[] = [
                "numero" => $numero,
                "valorAmortizacao" => $valorAmortizacao,
                "valorJuros" => $valorJuros,
                "valorPrestacao" => $valorPrestacao
            ];
            $saldoDevedor -= $valorAmortizacao;
        }
        return $parcelas;
    }
    
    private function calcularParcelasSac(float $valor, int $prazo): array
    {
        if ($prazo == 0) return [];
        $valorAmortizacao = round($valor / $prazo, 2);
        $saldoDevedor = round($valor, 2);
        $parcelas = [];
        for ($numero = 1; $numero <= $prazo; $numero++) {
            $valorJuros = round($saldoDevedor * $this->produto->obterPercentualTaxaJuros(), 2);
            $valorPrestacao = round($valorAmortizacao + $valorJuros, 2);
            $parcelas[] = [
                "numero" => $numero,
                "valorAmortizacao" => $valorAmortizacao,
                "valorJuros" => $valorJuros,
                "valorPrestacao" => $valorPrestacao
            ];
            $saldoDevedor -= $valorAmortizacao;
        }
        return $parcelas;
    }
    
    private function resultadoSimulacao(float $valor, int $prazo): array
    {
        return [
            [
                'tipo' => 'PRICE',
                'parcelas' => $this->calcularParcelasPrice($valor, $prazo)
            ],
            [
                'tipo' => 'SAC',
                'parcelas' => $this->calcularParcelasSac($valor, $prazo)
            ]
        ];
    }
    
    public function simular(float $valor, int $prazo): array
    {
        return [
            'codigoProduto' => $this->produto->obterCodigoProduto(),
            'descricaoProduto' => $this->produto->obterNomeProduto(),
            'taxaJuros' => $this->produto->obterPercentualTaxaJuros(),
            'resultadoSimulacao' => $this->resultadoSimulacao($valor, $prazo)
        ];
    }
    
}
