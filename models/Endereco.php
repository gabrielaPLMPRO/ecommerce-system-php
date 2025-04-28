<?php
require_once 'includes/db.connection.php';

class Endereco {
    public $id;
    public $rua;
    public $numero;
    public $complemento;
    public $bairro;
    public $cep;
    public $cidade;
    public $estado;

    // public function inserir($nome, $email, $senha, $tipo) {
    //     global $pdo;
    //     try {
    //         $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    //         $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
    //         $stmt = $pdo->prepare($sql);
            
    //         $stmt->bindParam(':nome', $nome);
    //         $stmt->bindParam(':email', $email);
    //         $stmt->bindParam(':senha', $senhaHash); 
    //         $stmt->bindParam(':tipo', $tipo);
            
    //         $stmt->execute();
    //         return true;
    //     } catch (PDOException $e) {
    //         return false;
    //     }
    // }
}
?>
