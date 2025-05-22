<?php

include_once('PostgresDao.php');

class PostgresEnderecoDao extends PostgresDao {

    private $table_name = 'endereco';
    
    public function insere($endereco) {

        $query = "INSERT INTO " . $this->table_name . 
        " (rua, numero, complemento, bairro, cep, cidade, estado) VALUES" .
        " (:rua, :numero, :complemento, :bairro, :cep, :cidade, :estado)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $rua = $endereco->getRua();
        $numero = $endereco->getNumero();
        $complemento = $endereco->getComplemento();
        $bairro = $endereco->getBairro();
        $cep = $endereco->getCep();
        $cidade = $endereco->getCidade();
        $estado = $endereco->getEstado();

        $stmt->bindParam(":rua", $rua);
        $stmt->bindParam(":numero", $numero );
        $stmt->bindParam(":complemento", $complemento);
        $stmt->bindParam(":bairro", $bairro);
        $stmt->bindParam(":cep", $cep);
        $stmt->bindParam(":cidade", $cidade);
        $stmt->bindParam(":estado", $estado);

        if($stmt->execute()){
            return $this->conn->lastInsertId();  // retorna o ID inserido
        } else {
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
    public function altera($endereco) {

        $query = "UPDATE " . $this->table_name . 
        " SET rua = :rua, numero = :numero, complemento = :complemento, bairro = :bairro, cep = :cep, cidade = :cidade, estado = :estado" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $rua = $endereco->getRua();
        $numero = $endereco->getNumero();
        $complemento = $endereco->getComplemento();
        $bairro = $endereco->getBairro();
        $cep = $endereco->getCep();
        $cidade = $endereco->getCidade();
        $estado = $endereco->getEstado();
        $id = $endereco->getId();

        $stmt->bindParam(":rua", $rua);
        $stmt->bindParam(":numero", $numero );
        $stmt->bindParam(":complemento", $complemento);
        $stmt->bindParam(":bairro", $bairro);
        $stmt->bindParam(":cep", $cep);
        $stmt->bindParam(":cidade", $cidade);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id", $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaPorId($id) {
        
        $endereco = null;

        $query = "SELECT
                    id, rua, numero, complemento, bairro, cep, cidade, estado
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
            $endereco = new Endereco($row['id'],$row['rua'], $row['numero'], $row['complemento'], $row['bairro'], $row['cep'], $row['cidade'], $row['estado']);
        } 
     
        return $endereco;
    }
}
?>
