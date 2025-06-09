<?php

include_once('PostgresFornecedorDao.php');
include_once('PostgresEnderecoDao.php');
include_once('PostgresProdutoDao.php');
include_once('PostgresEstoqueDao.php');
include_once('PostgresPedidoDao.php');
include_once('PostgresItensPedidoDao.php');
include_once('PostgresUsuarioDao.php');

class PostgresDaofactory {

    // specify your own database credentials
    private $username = "postgres";
    private $password = "ucs";
    public $conn;
  
    public function getConnection(){
  
        if($this->conn===null){
            try {
                $this->conn = new PDO("pgsql:host=localhost;port=5432;dbname=virtual_store", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
        }
        
        return $this->conn;
    }
    

    public function getFornecedorDao() {
        return new PostgresFornecedorDao($this->getConnection());
    }

    public function getEnderecoDao() {
        return new PostgresEnderecoDao($this->getConnection());
    }
    public function getProdutoDao() {
        return new PostgresProdutoDao($this->getConnection());
    }
    public function getEstoqueDao() {
        return new PostgresEstoqueDao($this->getConnection());
    }
    public function getPedidoDao() {
        return new PostgresPedidoDao($this->getConnection());
    }
    public function getItensPedidoDao(){
        return new PostgresItensPedidoDao($this->getConnection());
    }
    public function getUsuarioDao(){
        return new PostgresUsuarioDao($this->getConnection());
    }
}
?>
