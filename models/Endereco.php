<?php
require_once '../includes/db.connection.php';

class Endereco {
    public $rua;
    public $numero;
    public $complemento;
    public $bairro;
    public $cep;
    public $cidade;
    public $estado;

    public function salvar($conn) {
        $sql = "INSERT INTO endereco (rua, numero, complemento, bairro, cep, cidade, estado)
                VALUES (:rua, :numero, :complemento, :bairro, :cep, :cidade, :estado)
                RETURNING id";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':rua', $this->rua);
        $stmt->bindParam(':numero', $this->numero);
        $stmt->bindParam(':complemento', $this->complemento);
        $stmt->bindParam(':bairro', $this->bairro);
        $stmt->bindParam(':cep', $this->cep);
        $stmt->bindParam(':cidade', $this->cidade);
        $stmt->bindParam(':estado', $this->estado);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
}
?>