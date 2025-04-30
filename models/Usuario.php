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
    public static function listar($conn) {
        $sql = "SELECT * FROM usuarios 
                ORDER BY nome ASC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function buscar($conn, $termo) {
        $sql = "SELECT * FROM usuarios 
                WHERE id||nome ILIKE :termo";
        
        $stmt = $conn->prepare($sql);
    
        $likeTerm = "%" . $termo . "%";
        $stmt->bindValue(':termo', $likeTerm);
 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function atualizar($conn) {
        try {
            $conn->beginTransaction();
    
            $sqlUsuario = "UPDATE usuarios 
                              SET nome = ?, email = ?, senha = ?, tipo = ?
                              WHERE id = ?";
                              
            $stmt = $conn->prepare($sqlUsuario);
            $stmt->execute([
                $this->nome,
                $this->email,
                $this->senha,
                $this->tipo,
                $this->id
            ]);
    
            $conn->commit();
            return true;
    
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
    public function excluir($conn) {
        $conn->beginTransaction();

        try {
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$this->id]);

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}
?>
