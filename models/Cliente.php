<?php
class Cliente {
    public $id;
    public $nome;
    public $telefone;
    public $email;
    public $cartao_credito;
    public $endereco_id;
    public $usuario_id;

    public function __construct($id, $nome, $telefone, $email, $cartao_credito, $endereco_id, $usuario_id) {
        $this->id = $id;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->cartao_credito = $cartao_credito;
        $this->endereco_id = $endereco_id;
        $this->usuario_id = $usuario_id;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getCartaoCredito() { return $this->cartao_credito; }
    public function setCartaoCredito($cartao_credito) { $this->cartao_credito = $cartao_credito; }

    public function getEnderecoId() { return $this->endereco_id; }
    public function setEnderecoId($endereco_id) { $this->endereco_id = $endereco_id; }

    public function getUsuarioId() { return $this->usuario_id; }
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
}
?>