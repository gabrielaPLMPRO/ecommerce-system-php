<?php

include_once('PostgresDao.php');

class PostgresItensPedidoDao extends PostgresDao {

    private $table_name = 'itens_pedido';
    
    public function insere($pedido) {

        $query = "INSERT INTO " . $this->table_name . 
        " (pedido_id, produto_id, quantidade, preco_unitario, subtotal) VALUES" .
        " (:pedido_id, :produto_id, :quantidade, :preco_unitario, :subtotal)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $pedido_id = $pedido->getPedidoId();
        $produto_id = $pedido->getProdutoId();
        $quantidade = $pedido->getQuantidade();
        $preco_unitario = $pedido->getPrecoUnitario();
        $subtotal = $pedido->getSubtotal();

        $stmt->bindParam(":pedido_id", $pedido_id);
        $stmt->bindParam(":produto_id", $produto_id );
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":preco_unitario", $preco_unitario);
        $stmt->bindParam(":subtotal", $subtotal);
      
        if($stmt->execute()){
            return $this->conn->lastInsertId();  // retorna o ID inserido
        } else {
            return false;
        }
    }

    public function buscaPorId($id) {
        
        $itemPedido = null;

        $query = "SELECT
                    id, pedido_id, produto_id, quantidade, preco_unitario, subtotal
                FROM
                    " . $this->table_name . "
                WHERE
                    id = ?
                LIMIT
                    1 OFFSET 0";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, (int)$id, PDO::PARAM_INT);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $itemPedido = new ItemPedido($row['id'],$row['pedido_id'], $row['produto_id'], $row['quantidade'],$row['preco_unitario'],$row['subtotal']);
        } 
     
        return $itemPedido;
    }
}
?>
