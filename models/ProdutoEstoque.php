<?php
class ProdutoEstoque {
    private $produto;
    private $estoque;
    private $preco;

    public function __construct(Produto $produto, int $estoque, float $preco) {
        $this->produto = $produto;
        $this->estoque = $estoque;
        $this->preco = $preco;
    }

    public function getProduto(): Produto {
        return $this->produto;
    }

    public function getEstoque(): int {
        return $this->estoque;
    }

    public function getPreco(): float {
        return $this->preco;
    }

    public function getFornecedorId() {
        return $this->produto->getFornecedorId();
    }

    public function getId() {
        return $this->produto->getId();
    }

    public function getNome() {
        return $this->produto->getNome();
    }

    public function getDescricao() {
        return $this->produto->getDescricao();
    }
}
?>

