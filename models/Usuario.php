<?php
class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $tipo;

    public function __construct( $id, $nome, $email, $senha, $tipo)
    {
        $this->id=$id;
        $this->nome=$nome;
        $this->email=$email;
        $this->senha=$senha;
        $this->tipo=$tipo;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getNome() { return $this->nome; }
    public function setNome($nome) {$this->nome = $nome;}

    public function getEmail() { return $this->email; }
    public function setEmail($email) {$this->email = $email;}

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) {$this->senha = $senha;}

    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) {$this->tipo = $tipo;}
}
?>
