<?php

include_once('PostgresDao.php');

class PostgresEstoqueDao extends PostgresDao {

    private $table_name = 'estoque';
    
    public function insere($estoque) {

        $query = "INSERT INTO " . $this->table_name . 
        " (preco, estoque, produto_id) VALUES" .
        " (:preco, :estoque, :produto_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $preco = $estoque->getPreco();
        $estoque = $estoque->getEstoque();
        $produto_id = $estoque->getProdutoId();

        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":estoque", $estoque );
        $stmt->bindParam(":produto_id", $produto_id);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . 
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }
    public function altera($estoque) {

        $query = "UPDATE " . $this->table_name . 
        " SET preco = :preco, estoque = :estoque" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $preco = $estoque->getPreco();
        $estoque = $estoque->getEstoque();
        $id = $estoque->getId();

        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":estoque", $estoque);
        $stmt->bindParam(":id", $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaPorId($id) {
        
        $estoque = null;

        $query = "SELECT
                    id, preco, estoque, produto_id
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
            $estoque = new Estoque($row['id'],$row['preco'], $row['estoque'], $row['produto_id']);
        } 
     
        return $estoque;
    }

    public function buscaComNomePaginado($nome,$inicio,$quantos) {
        $estoques = array();

        $query = "SELECT
                    id, preco, estoque, produto_id
                FROM
                    " . $this->table_name . 
                    " WHERE UPPER(nome) LIKE ?" .
                    " ORDER BY id ASC" .
                    " LIMIT ? OFFSET ?";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, '%' . strtoupper($nome) . '%');
        $stmt->bindValue(2, $quantos);
        $stmt->bindValue(3, $inicio);
        $stmt->execute();

        $filter_query = $query . "LIMIT " .$quantos. " OFFSET " . $inicio . '';
        error_log("---> DAO Query : " . $filter_query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $estoques[] = new Estoque($id,$preco,$estoque,$produto_id);
        }
        
        return $estoques;
    }

    public function contaComNome($nome) {

        $quantos = 0;

        $query = "SELECT COUNT(*) AS contagem FROM " . 
                    $this->table_name .
                    " WHERE UPPER(nome) LIKE ? ";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, '%' . strtoupper($nome) . '%');
        
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $quantos = $contagem;
        }
        
        return $quantos;
    }
}
?>
