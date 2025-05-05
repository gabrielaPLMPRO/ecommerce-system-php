<?php
require_once '../includes/db.connection.php';

class Fornecedor {
    public $id;
    public $nome;
    public $descricao;
    public $telefone;
    public $email;
    public $endereco_id;

    public function __construct($id = null, $nome = '', $descricao = '', $telefone = '', $email = '', $endereco_id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->endereco_id = $endereco_id;
    }

    public function salvar($conn) {
        $sql = "INSERT INTO fornecedores (nome, descricao, telefone, email, endereco_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->nome,
            $this->descricao,
            $this->telefone,
            $this->email,
            $this->endereco_id
        ]);
    }

    public function atualizar($conn) {
        try {
            $conn->beginTransaction();
    
            $sqlFornecedor = "UPDATE fornecedores 
                              SET nome = ?, descricao = ?, telefone = ?, email = ?
                              WHERE id = ?";
            $stmt = $conn->prepare($sqlFornecedor);
            $stmt->execute([
                $this->nome,
                $this->descricao,
                $this->telefone,
                $this->email,
                $this->id
            ]);
    
            $sqlEndereco = "UPDATE endereco 
                            SET rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?, cep = ?
                            WHERE id = ?";
            $stmt = $conn->prepare($sqlEndereco);
            $stmt->execute([
                $_POST['rua'],
                $_POST['numero'],
                $_POST['complemento'],
                $_POST['bairro'],
                $_POST['cidade'],
                $_POST['estado'],
                $_POST['cep'],
                $this->endereco_id
            ]);
    
            $conn->commit();
            return true;
    
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    public function excluir($conn) {
        $stmt = $conn->prepare("SELECT endereco_id FROM fornecedores WHERE id = ?");
        $stmt->execute([$this->id]);
        $endereco = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$endereco) {
            return false;
        }
    
        $conn->beginTransaction();
    
        try {
            $stmt = $conn->prepare("DELETE FROM fornecedores WHERE id = ?");
            $stmt->execute([$this->id]);
    
            $stmt = $conn->prepare("DELETE FROM endereco WHERE id = ?");
            $stmt->execute([$endereco['endereco_id']]);
    
            $conn->commit();
            return true;
    
        } catch (Exception $e) {
            error_log("Erro ao excluir fornecedor: " . $e->getMessage());
            $conn->rollBack();
            return false;
        }
    }

    public static function listar($conn) {
        $sql = "SELECT f.id AS fornecedor_id, f.nome, f.descricao, f.telefone, f.email, 
                           f.endereco_id, f.created_at, f.updated_at,
                           e.id AS endereco_id, e.rua, e.numero, e.complemento, 
                           e.bairro, e.cep, e.cidade, e.estado
                    FROM fornecedores f
                    JOIN endereco e ON f.endereco_id = e.id
                    ORDER BY f.nome ASC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function buscar($conn, $termo) {
        $sql = "SELECT f.*, e.* FROM fornecedores f
                JOIN endereco e ON f.endereco_id = e.id
                WHERE f.id::text ILIKE :termo OR f.nome ILIKE :termo";
        
        $stmt = $conn->prepare($sql);
    
        $likeTerm = "%" . $termo . "%";
        $stmt->bindValue(':termo', $likeTerm);
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorId($conn, $id) {
        $sql = "SELECT f.*, e.* FROM fornecedores f
                JOIN endereco e ON f.endereco_id = e.id
                WHERE f.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
