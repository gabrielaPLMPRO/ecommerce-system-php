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
}
?>
