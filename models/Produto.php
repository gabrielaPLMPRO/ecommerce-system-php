<?php
require_once '../includes/db.connection.php';

class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $foto;
    public $fornecedor_id;

    public function salvar($conn) {
        $sql = "INSERT INTO produtos (nome, descricao, fornecedor_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->nome,
            $this->descricao,
            $this->fornecedor_id
        ]);
    }
    public static function listar($conn) {
        $sql = "SELECT p.id id, p.nome nome, p.descricao descricao, p.fornecedor_id, f.nome fornecedor FROM produtos p, fornecedores f
                WHERE f.id=p.fornecedor_id
                ORDER BY nome ASC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function buscar($conn, $termo) {
        $sql = "SELECT p.id id, p.nome nome, p.descricao descricao, p.fornecedor_id, f.nome fornecedor FROM produtos p, fornecedores f
                WHERE p.id||p.nome||p.descricao ILIKE :termo
                AND f.id=p.fornecedor_id";
        
        $stmt = $conn->prepare($sql);
    
        $likeTerm = "%" . $termo . "%";
        $stmt->bindValue(':termo', $likeTerm);
 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorId($conn, $id) {
        $sql = "SELECT p.id, p.nome, p.descricao, p.foto, p.fornecedor_id, f.nome AS fornecedor_nome
                FROM produtos p
                LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
                WHERE p.id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function atualizar($conn) {
        try {
            $conn->beginTransaction();
    
            $sql = "UPDATE produtos 
                              SET nome = ?, descricao = ?, fornecedor_id = ?
                              WHERE id = ?";
                              
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $this->nome,
                $this->descricao,
                $this->fornecedor_id,
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
            $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
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
