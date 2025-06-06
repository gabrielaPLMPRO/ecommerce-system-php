<?php
class ItemPedido {
    public $id;
    public $pedido_id;
    public $produto_id;
    public $quantidade;
    public $preco_unitario;
    public $subtotal;


    public function __construct( $id, $pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal)
    {
        $this->id=$id;
        $this->pedido_id=$pedido_id;
        $this->produto_id=$produto_id;
        $this->quantidade=$quantidade;
        $this->preco_unitario=$preco_unitario;
        $this->subtotal=$subtotal;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getPedidoId() { return $this->pedido_id; }
    public function setPedidoId($pedido_id) {$this->pedido_id = $pedido_id;}

    public function getProdutoId() { return $this->produto_id; }
    public function setProdutoId($produto_id) {$this->produto_id = $produto_id;}

    public function getQuantidade() { return $this->quantidade; }
    public function setQuantidade($quantidade) {$this->quantidade = $quantidade;}
    
    public function getPrecoUnitario() { return $this->preco_unitario; }
    public function setPrecoUnitario($preco_unitario) {$this->preco_unitario = $preco_unitario;}

    public function getSubtotal() { return $this->subtotal; }
    public function setSubtotal($subtotal) {$this->subtotal = $subtotal;}
}
?>
