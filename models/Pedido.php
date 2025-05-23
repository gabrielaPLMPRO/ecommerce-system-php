<?php
require_once 'includes/db.connection.php';

class Pedido {
    public $id;
    public $cliente_id;
    public $usuario_id;
    public $status;
    public $numero;
    public $data_pedido;
    public $data_entrega;
    public $total;
}
?>
