<?php
class Pedido {
    public $id;
    public $cliente_id;
    public $usuario_id;
    public $status;
    public $numero;
    public $data_pedido;
    public $data_entrega;
    public $total;


    public function __construct( $id, $cliente_id, $usuario_id, $status, $numero, $data_pedido, $data_entrega, $total)
    {
        $this->id=$id;
        $this->cliente_id=$cliente_id;
        $this->usuario_id=$usuario_id;
        $this->status=$status;
        $this->numero=$numero;
        $this->data_pedido=$data_pedido;
        $this->data_entrega=$data_entrega;
        $this->total=$total;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getClienteId() { return $this->cliente_id; }
    public function setClienteId($cliente_id) {$this->cliente_id = $cliente_id;}

    public function getUsuarioId() { return $this->usuario_id; }
    public function setUsuarioId($usuario_id) {$this->usuario_id = $usuario_id;}

    public function getStatus() { return $this->status; }
    public function setStatus($status) {$this->status = $status;}
    
    public function getNumero() { return $this->numero; }
    public function setNumero($numero) {$this->numero = $numero;}

    public function getDataPedido() { return $this->data_pedido; }
    public function setDataPedido($data_pedido) {$this->data_pedido = $data_pedido;}

    public function getDataEntrega() { return $this->data_entrega; }
    public function setDataEntrega($data_entrega) {$this->data_entrega = $data_entrega;}

    public function getTotal() { return $this->total; }
    public function setTotal($total) {$this->total = $total;}
}
?>
