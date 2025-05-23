<?php
require_once 'includes/db.connection.php';

class ItemPedido {
    public $id;
    public $pedido_id;
    public $produto_id;
    public $quantidade;
    public $preco_unitario;
    public $subtotal;
}
?>
