<?php
class Estoque {
    public $id;
    public $preco;
    public $estoque;
    public $produto_id;

    public function __construct( $id, $preco, $estoque, $produto_id)
    {
        $this->id=$id;
        $this->preco=$preco;
        $this->estoque=$estoque;
        $this->produto_id=$produto_id;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getPreco() { return $this->preco; }
    public function setPreco($preco) {$this->preco = $preco;}

    public function getEstoque() { return $this->estoque; }
    public function setEstoque($estoque) {$this->estoque = $estoque;}

    public function getProdutoId() { return $this->produto_id; }
    public function setProdutoId($produto_id) {$this->produto_id = $produto_id;}
}
