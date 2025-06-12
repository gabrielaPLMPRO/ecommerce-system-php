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
        $qtdEstoque = $estoque->getEstoque();
        $produto_id = $estoque->getProdutoId();

        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":estoque", $qtdEstoque );
        $stmt->bindParam(":produto_id", $produto_id);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

   public function removePorProdutoId($produto_id) {
    $query = "DELETE FROM " . $this->table_name . 
    " WHERE produto_id = :produto_id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindValue(':produto_id', (int)$produto_id, PDO::PARAM_INT);

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
        $qtdEstoque = $estoque->getEstoque();
        $id = $estoque->getId();

        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":estoque", $qtdEstoque);
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
                    e.id, e.preco, e.estoque, e.produto_id
                FROM
                    " . $this->table_name . " e, produtos p".
                    " WHERE UPPER(p.nome) LIKE ?" .
                    " and e.produto_id=p.id".
                    " ORDER BY id ASC" .
                    " LIMIT ? OFFSET ?";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, '%' . strtoupper($nome) . '%');
        $stmt->bindValue(2, $quantos);
        $stmt->bindValue(3, $inicio);
        $stmt->execute();

        $filter_query = $query . "LIMIT " .$quantos. " OFFSET " . $inicio . '';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $estoques[] = new Estoque($id,$preco,$estoque,$produto_id);
        }
        
        return $estoques;
    }

    public function contaComNome($nome) {

        $quantos = 0;

        $query = "SELECT COUNT(*) AS contagem FROM " . 
                    $this->table_name ." e, produtos p where p.id=e.produto_id".
                    " and UPPER(p.nome) LIKE ? ";
     
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
