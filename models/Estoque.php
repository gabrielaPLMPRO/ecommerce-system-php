<?php
require_once __DIR__ . '/../includes/db.connection.php';

class Estoque {
    public $id;
    public $preco;
    public $estoque;
    public $produto_id;

    public static function listarComProduto($busca = '') {
        $pdo = getConnection();

        if ($pdo === null) {
            echo "Erro: Não foi possível conectar ao banco de dados.";
            exit;
        }

        try {
            $sql = "SELECT p.nome, e.preco, e.estoque, e.produto_id
            FROM estoque e
            JOIN produtos p ON e.produto_id = p.id
            WHERE p.nome LIKE :busca OR CAST(p.id AS TEXT) LIKE :busca";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':busca', '%' . $busca . '%');

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erro ao consultar estoque: " . $e->getMessage();
            exit;
        }
    }

    public static function buscarPorProdutoId($conn, $produto_id) {
        $sql = "SELECT * FROM estoque WHERE produto_id = :produto_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $pdo = getConnection();

        if ($pdo === null) {
            echo "Erro: Não foi possível conectar ao banco de dados.";
            exit;
        }

        try {
            $sql = "UPDATE estoque SET preco = :preco, estoque = :estoque, updated_at = CURRENT_TIMESTAMP WHERE produto_id = :produto_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':preco', $this->preco);
            $stmt->bindParam(':estoque', $this->estoque);
            $stmt->bindParam(':produto_id', $this->produto_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar estoque: " . $e->getMessage();
            exit;
        }
    }

    public static function excluirPorProdutoId($produto_id) {
        $pdo = getConnection();
    
        if ($pdo === null) {
            echo "Erro: Não foi possível conectar ao banco de dados.";
            exit;
        }
    
        try {
            $sql = "DELETE FROM estoque WHERE produto_id = :produto_id";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
    
            return $stmt->execute();
    
        } catch (PDOException $e) {
            echo "Erro ao excluir estoque: " . $e->getMessage();
            return false;
        }
    }
    
}
?>
