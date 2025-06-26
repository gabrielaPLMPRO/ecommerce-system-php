<?php

include_once('PostgresDao.php');

class PostgresPedidoDao extends PostgresDao {

    private $table_name = 'pedidos';
    
    public function insere($pedido) {

        $query = "INSERT INTO " . $this->table_name . 
        " (cliente_id, usuario_id, status, numero, data_pedido, data_entrega, total) VALUES" .
        " (:cliente_id, :usuario_id, :status, :numero, :data_pedido, :data_entrega, :total)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $cliente_id = $pedido->getClienteId();
        $usuario_id = $pedido->getUsuarioId();
        $status = $pedido->getStatus();
        $numero = $pedido->getNumero();
        $data_pedido = $pedido->getDataPedido();
        $data_entrega = $pedido->getDataEntrega();
        $total = $pedido->getTotal();

        $stmt->bindParam(":cliente_id", $cliente_id);
        $stmt->bindParam(":usuario_id", $usuario_id );
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":numero", $numero);
        $stmt->bindParam(":data_pedido", $data_pedido);
        $stmt->bindParam(":data_entrega", $data_entrega);
        $stmt->bindParam(":total", $total);
      
        if($stmt->execute()){
            return $this->conn->lastInsertId();  // retorna o ID inserido
        } else {
            return false;
        }
    }

    public function altera($pedido) {

        $query = "UPDATE " . $this->table_name . 
        " SET status = :status, data_entrega = :data_entrega" . //coloquei propositalmente só esses campos pois na regra de negócio não se pode alterar as outras informações
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $status = $pedido->getStatus();
        $data_entrega = $pedido->getDataEntrega();
        $id = $pedido->getId();

        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":data_entrega", $data_entrega);
        $stmt->bindParam(":id", $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaPorId($id) {
        
        $pedido = null;

        $query = "SELECT
                    id, cliente_id, usuario_id, status, numero, data_pedido, data_entrega, total
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
            $pedido = new Pedido($row['id'],$row['cliente_id'], $row['usuario_id'], $row['status'],$row['numero'],$row['data_pedido'],$row['data_entrega'],$row['total']);
        } 
     
        return $pedido;
    }

    public function buscaComNomePaginado($filtro, $inicio, $quantos, $idUsuarioLogado) {
        $pedidos = array();
        $isNumero = is_numeric($filtro);

        $query = "SELECT
                    p.id, p.cliente_id, p.usuario_id, p.status, p.numero, p.data_pedido, p.data_entrega, p.total
                FROM " . $this->table_name . " p
                JOIN clientes c ON c.id = p.cliente_id
                WHERE (UPPER(c.nome) LIKE ?";

        if ($isNumero) {
            $query .= " OR p.numero = ?";
        }

        if ($idUsuarioLogado) {
            $query .= ") AND p.usuario_id = ?";
        } else {
            $query .= ")";
        }

        $query .= " ORDER BY p.id ASC LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($query);

        // Bind dos parâmetros
        $paramIndex = 1;
        $stmt->bindValue($paramIndex++, '%' . strtoupper($filtro) . '%');

        if ($isNumero) {
            $stmt->bindValue($paramIndex++, $filtro, PDO::PARAM_STR); // `numero` pode ser string
        }

        if ($idUsuarioLogado) {
            $stmt->bindValue($paramIndex++, (int)$idUsuarioLogado, PDO::PARAM_INT);
        }

        $stmt->bindValue($paramIndex++, $quantos, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, $inicio, PDO::PARAM_INT);

        $stmt->execute();

        $filter_query = $query . " LIMIT " . $quantos . " OFFSET " . $inicio;
        error_log("---> DAO Query : " . $filter_query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $pedidos[] = new Pedido($id, $cliente_id, $usuario_id, $status, $numero, $data_pedido, $data_entrega, $total);
        }

        return $pedidos;
    }


    public function contaComNome($filtro, $idUsuarioLogado) {
        $quantos = 0;
        $isNumero = is_numeric($filtro);

        $query = "SELECT COUNT(*) AS contagem
                FROM " . $this->table_name . " p
                JOIN clientes c ON c.id = p.cliente_id
                WHERE (UPPER(c.nome) LIKE ?";

        if ($isNumero) {
            $query .= " OR p.numero = ?";
        }

        if ($idUsuarioLogado) {
            $query .= ") AND p.usuario_id = ?";
        } else {
            $query .= ")";
        }

        $stmt = $this->conn->prepare($query);

        // Bind dos parâmetros
        $paramIndex = 1;
        $stmt->bindValue($paramIndex++, '%' . strtoupper($filtro) . '%');

        if ($isNumero) {
            $stmt->bindValue($paramIndex++, $filtro, PDO::PARAM_STR);
        }

        if ($idUsuarioLogado) {
            $stmt->bindValue($paramIndex++, (int)$idUsuarioLogado, PDO::PARAM_INT);
        }

        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quantos = $contagem;
        }

        return $quantos;
    }

}
?>
