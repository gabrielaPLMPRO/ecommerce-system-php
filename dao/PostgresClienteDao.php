<?php

include_once('PostgresDao.php');

class PostgresClienteDao extends PostgresDao {

    private $table_name = 'clientes';
    
    public function insere($cliente) {

        $query = "INSERT INTO " . $this->table_name . 
        " (nome, telefone, email, cartao_credito, endereco_id, usuario_id) VALUES" .
        " (:nome, :telefone, :email, :cartao_credito, :endereco_id, :usuario_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $nome = $cliente->getNome();
        $telefone = $cliente->getTelefone();
        $email = $cliente->getEmail();
        $cartao_credito = $cliente->getCartaoCredito();
        $endereco_id = $cliente->getEnderecoId();
        $usuario_id = $cliente->getUsuarioId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":telefone", $telefone );
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":cartao_credito", $cartao_credito);
        $stmt->bindParam(":endereco_id", $endereco_id);
        $stmt->bindParam(":usuario_id", $usuario_id);
      
        if($stmt->execute()){
            return $this->conn->lastInsertId();  // retorna o ID inserido
        } else {
            return false;
        }
    }

    // public function __construct($id, $nome, $telefone, $email, $cartao_credito, $endereco_id, $usuario_id) {

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
    public function altera($cliente) {

        $query = "UPDATE " . $this->table_name . 
        " SET nome = :nome, telefone = :telefone, email = :email, cartao_credito = :cartao_credito" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $nome = $cliente->getNome();
        $telefone = $cliente->getTelefone();
        $email = $cliente->getEmail();
        $cartao_credito = $cliente->getCartaoCredito();
        $id = $cliente->getId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":cartao_credito", $cartao_credito);
        $stmt->bindParam(":id", $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaPorId($id) {
        
        $cliente = null;

        $query = "SELECT
                    id, nome, telefone, email, cartao_credito, endereco_id, usuario_id
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
            $cliente = new Cliente($row['id'],$row['nome'], $row['telefone'], $row['email'], $row['cartao_credito'], $row['endereco_id'], $row['usuario_id']);
        } 
     
        return $cliente;
    }

    public function buscaComNomePaginado($nome,$inicio,$quantos) {
        $clientes = array();

        $query = "SELECT
                    id, nome, telefone, email, cartao_credito, endereco_id, usuario_id
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
            $clientes[] = new Cliente($id,$nome,$telefone,$email,$cartao_credito,$endereco_id,$usuario_id);
        }
        
        return $clientes;
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

