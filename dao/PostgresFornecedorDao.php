<?php

include_once('PostgresDao.php');

class PostgresFornecedorDao extends PostgresDao {

    private $table_name = 'fornecedores';
    
    public function insere($fornecedor) {

        $query = "INSERT INTO " . $this->table_name . 
        " (nome, descricao, telefone, email, endereco_id) VALUES" .
        " (:nome, :descricao, :telefone, :email, :endereco_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $nome = $fornecedor->getNome();
        $descricao = $fornecedor->getDescricao();
        $telefone = $fornecedor->getTelefone();
        $email = $fornecedor->getEmail();
        $endereco_id = 4;

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao );
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":endereco_id", $endereco_id);

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
    public function altera($fornecedor) {

        $query = "UPDATE " . $this->table_name . 
        " SET nome = :nome, descricao = :descricao, telefone = :telefone, email = :email" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $nome = $fornecedor->getNome();
        $descricao = $fornecedor->getDescricao();
        $telefone = $fornecedor->getTelefone();
        $email = $fornecedor->getEmail();
        $id = $fornecedor->getId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao );
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaPorId($id) {
        
        $fornecedor = null;

        $query = "SELECT
                    id, nome, descricao, telefone, email, endereco_id
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
            $fornecedor = new Fornecedor($row['id'],$row['nome'], $row['descricao'], $row['telefone'], $row['email'], $row['endereco_id']);
        } 
     
        return $fornecedor;
    }

    public function buscaPorNomeCom($palavra) {
        
 	$fornecedores = array();        
	
        $query = "SELECT
                    id, nome, descricao, telefone, email, endereco_id
                FROM
                    " . $this->table_name . "
                WHERE
                    nome like ? ORDER BY id ASC";
     
        $stmt = $this->conn->prepare($query);
	    $parametro = "%" . $palavra . "%";
        $stmt->bindParam(1, $parametro);
        $stmt->execute();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $fornecedores[] = new Fornecedor($id,$nome,$descricao,$telefone, $email, $endereco_id);
        }
	
        return $fornecedores;
    }

    public function buscaJSONPorNomeCom($palavra) {
        $fornecedores = $this->buscaPorNomeCom($palavra);
        $fornecedoresJSON = array();
        foreach ($fornecedores as $fornecedor) {
            $fornecedoresJSON[] = $fornecedor->getDadosParaJSON();
        }
        return stripslashes(json_encode($fornecedoresJSON,JSON_PRETTY_PRINT));
    }

    public function contaTodos() {

        $quantos = 0;

        $query = "SELECT COUNT(*) AS contagem FROM " . 
                    $this->table_name;
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $quantos = $contagem;
        }
        
        return $quantos;
    }

    public function buscaTodos() {

        $fornecedores = array();

        $query = "SELECT
                    id, nome, descricao, telefone, email, endereco_id
                FROM
                    " . $this->table_name . 
                    " ORDER BY id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $fornecedores[] = new Fornecedor($id,$nome,$descricao,$telefone, $email, $endereco_id);
        }
        
        return $fornecedores;
    }

    public function buscaTodosPaginado($inicio,$quantos) {
        $fornecedores = array();

        $query = "SELECT
                    id, nome, descricao, telefone, email, endereco_id
                FROM
                    " . $this->table_name . 
                    " ORDER BY id ASC" .
                    " LIMIT ? OFFSET ?";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $quantos);
        $stmt->bindParam(2, $inicio);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $fornecedores[] = Fornecedor($id,$nome,$descricao,$telefone, $email, $endereco_id);
        }
        
        return $fornecedores;
    }

    public function buscaTodosPaginadoJSON($inicio,$quantos) {
        $fornecedores = $this->buscaTodosPaginado($inicio,$quantos);
        $fornecedoresJSON = array();
        foreach ($fornecedores as $fornecedor) {
            $fornecedoresJSON[] = $fornecedor->getDadosParaJSON();
        }
        return stripslashes(json_encode($fornecedoresJSON,JSON_PRETTY_PRINT));
    }

    public function buscaComNomePaginado($nome,$inicio,$quantos) {
        $fornecedores = array();

        $query = "SELECT
                    id, nome, descricao, telefone, email, endereco_id
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
            $fornecedores[] = new Fornecedor($id,$nome,$descricao,$telefone, $email, $endereco_id);
        }
        
        return $fornecedores;
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
