<?php

include_once('PostgresDao.php');

class PostgresUsuarioDao extends PostgresDao {

    private $table_name = 'usuarios';
    
    public function insere($usuario) {

        $query = "INSERT INTO " . $this->table_name . 
        " (nome, email, senha, tipo) VALUES" .
        " (:nome, :email, :senha, :tipo)";

        $stmt = $this->conn->prepare($query);

        // bind values 

        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $senha = md5($usuario->getEmail().$usuario->getSenha());
        $tipo = $usuario->getTipo();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email );
        $stmt->bindParam(":senha", $senha);
        $stmt->bindParam(":tipo", $tipo);
      
        if($stmt->execute()){
            return $this->conn->lastInsertId();  // retorna o ID inserido
        } else {
            return false;
        }
    }

    public function altera($usuario) {

        $query = "UPDATE " . $this->table_name . 
        " SET nome = :nome, email = :email, senha = :senha, tipo = :tipo" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $senha = md5($usuario->getEmail().$usuario->getSenha());
        $id = $usuario->getId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha);
        $stmt->bindParam(":id", $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaPorId($id) {
        
        $usuario = null;

        $query = "SELECT
                    id, nome, email, senha, tipo
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
            $usuario = new Usuario($row['id'],$row['nome'], $row['email'], $row['senha'],$row['tipo']);
        } 
     
        return $usuario;
    }
    public function buscarPorLogin($login) {
        
        $usuario = null;

        $query = "SELECT
                    id, nome, email, senha, tipo
                FROM
                    " . $this->table_name . "
                WHERE
                    UPPER(email) LIKE ?
                LIMIT
                    1 OFFSET 0";
        
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, strtoupper($login) );
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $usuario = new Usuario($row['id'],$row['nome'], $row['email'], $row['senha'],$row['tipo']);
        } 
        
        return $usuario;
    }

    public function buscaComNomePaginado($nome,$inicio,$quantos) { // tem que ter como pesquisar pelo nome do cliente e numero do pedido
        $usuarios = array();

        $query = "SELECT
                    id, nome, email, senha, tipo
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
            $usuarios[] = new Usuario($id,$nome,$email,$senha,$tipo);
        }
        
        return $usuarios;
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
