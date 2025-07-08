<?php

include_once('PostgresDao.php');

class PostgresItensPedidoDao extends PostgresDao {

    private $table_name = 'itens_pedido';
    
    public function insere($pedido) {

        $query = "INSERT INTO " . $this->table_name . 
        " (pedido_id, produto_id, quantidade, preco_unitario, subtotal) VALUES" .
        " (:pedido_id, :produto_id, :quantidade, :preco_unitario, :subtotal)";

        $stmt = $this->conn->prepare($query);


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
            return $this->conn->lastInsertId();  
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

    public function buscaPorPedidoId($pedido_id) { 
        $itens = array();

        $query = "SELECT
                    id, pedido_id, produto_id, quantidade, preco_unitario, subtotal
                FROM
                    " . $this->table_name . 
                    "  WHERE pedido_id = ?
                     ORDER BY id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, (int)$pedido_id, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $itens[] = new ItemPedido($id,$pedido_id,$produto_id,$quantidade,$preco_unitario,$subtotal);
        }
        
        return $itens;
    }
    public function buscaPorPedidoIdApi($pedido_id) { 
        $itens = array();

        $query = "SELECT
                    p.nome, p.descricao, i.preco_unitario, i.subtotal
                FROM
                    " . $this->table_name . " i, produtos p ". 
                    "  WHERE pedido_id = ? AND i.produto_id = p.id ORDER BY i.id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, (int)$pedido_id, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
             $itens[] = [
                'nome' => $nome,
                'descricao' => $descricao,
                'preco_unitario' => $preco_unitario,
                'subtotal' => $subtotal
            ];
        }
        
        return $itens;
    }
   private function getDadosParaJSON($item){

        $data = [
            'nome' => $item['nome'],
            'descricao' => $item['descricao'],
            'preco_unitario' => $item['preco_unitario'],
            'subtotal' => $item['subtotal']
        ];
        return $data;
    }

    public function buscaPorItensJSON($idPedido) {
        $itens = $this->buscaPorPedidoIdApi($idPedido);
        $itensJSON = array();
        foreach ($itens as $item) {
            $itensJSON[] =$this->getDadosParaJSON($item);
        }
        return $itensJSON;
    }
}
?>
