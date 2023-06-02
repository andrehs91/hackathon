<?php

namespace Hackathon\Dao;

use PDO;
use Hackathon\Model\Produto;

class ProdutoDao
{
    
    public function __construct(
        private PDO $pdo
    ) {}
    
    private function produto(array $produtoData): Produto
    {
        return new Produto(
            codigoProduto: $produtoData['CO_PRODUTO'],
            nomeProduto: $produtoData['NO_PRODUTO'],
            percentualTaxaJuros: $produtoData['PC_TAXA_JUROS'],
            numeroMinimoMeses: $produtoData['NU_MINIMO_MESES'],
            numeroMaximoMeses: $produtoData['NU_MAXIMO_MESES'],
            valorMinimo: $produtoData['VR_MINIMO'],
            valorMaximo: $produtoData['VR_MAXIMO']
        );
    }
    
    private function produtos(array $produtosData): array
    {
        $produtos = [];
        foreach ($produtosData as $produtoData) {
            $produtos[] = $this->produto($produtoData);
        }
        return $produtos;
    }
    
    public function buscarTodos(): array
    {
        $query = 'SELECT [CO_PRODUTO]
                ,[NO_PRODUTO]
                ,[PC_TAXA_JUROS]
                ,[NU_MINIMO_MESES]
                ,[NU_MAXIMO_MESES]
                ,[VR_MINIMO]
                ,[VR_MAXIMO]
            FROM [hack].[dbo].[PRODUTO]';
        $statement = $this->pdo->query($query);
        $produtosData = $statement->fetchAll();
        if (count($produtosData)) return $this->produtos($produtosData);
        return [];
    }
    
    public function buscarPorParametros(float $valor, int $prazo): ?Produto
    {
        // Conversão utilizada para evitar "Arithmetic overflow error"
        $valorFormatado = sprintf('%.2f', $valor);
        
        $query = "SELECT [CO_PRODUTO]
                ,[NO_PRODUTO]
                ,[PC_TAXA_JUROS]
                ,[NU_MINIMO_MESES]
                ,[NU_MAXIMO_MESES]
                ,[VR_MINIMO]
                ,[VR_MAXIMO]
            FROM [hack].[dbo].[PRODUTO]
            WHERE [NU_MINIMO_MESES] <= :prazo_minimo
                AND ([NU_MAXIMO_MESES] >= :prazo_maximo OR [NU_MAXIMO_MESES] IS NULL)
                AND [VR_MINIMO] <= {$valorFormatado}
                AND ([VR_MAXIMO] >= {$valorFormatado} OR [VR_MAXIMO] IS NULL)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':prazo_minimo', $prazo);
        $statement->bindValue(':prazo_maximo', $prazo);
        $statement->execute();
        $produtoData = $statement->fetch();
        if ($produtoData) return $this->produto($produtoData);
        return null;
    }
    
}
