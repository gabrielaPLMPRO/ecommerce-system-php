<?php

include_once('DaoFactory.php');
include_once('PostgresFornecedorDao.php');
include_once('PostgresEnderecoDao.php');

class PostgresDaofactory extends DaoFactory {

    // specify your own database credentials
    private $username = "postgres";
    private $password = "ucs";
    public $conn;
  
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("pgsql:host=localhost;port=5432;dbname=virtual_store", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
      }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
    

    public function getFornecedorDao() {
        return new PostgresFornecedorDao($this->getConnection());
    }

    public function getEnderecoDao() {
        return new PostgresEnderecoDao($this->getConnection());
    }
}
?>
