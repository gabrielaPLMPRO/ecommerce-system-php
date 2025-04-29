<?php
require_once '../includes/db.connection.php';

class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $tipo;

    public function buscaPorLogin($conn, $email) {

        $usuario = null;

        $sql = "SELECT
                    id, nome, email, senha, tipo
                FROM
                     usuarios 
                WHERE
                    email = ?
                LIMIT
                    1 OFFSET 0";
     
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam(1, $email);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $usuario = new Usuario();
            $usuario->id = $row['id'];
            $usuario->nome = $row['nome'];
            $usuario->email = $row['email'];
            $usuario->senha = $row['senha'];
            $usuario->tipo = $row['tipo'];
        } 
     
        return $usuario;
    }
    public function salvar($conn) {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->nome,
            $this->email,
            $this->senha,
            'cliente'
        ]);
    }

}
?>
