<?php
require_once 'includes/db.connection.php';

class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $tipo;

    public function cadastrar($nome, $email, $senha, $tipo) {
        global $pdo;
        try {
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senhaHash); 
            $stmt->bindParam(':tipo', $tipo);
            
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($email, $senha) {
        global $pdo;
        
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }

        return false;
    }
}
?>
