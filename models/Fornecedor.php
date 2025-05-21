<?php
class Fornecedor {
    public $id;
    public $nome;
    public $descricao;
    public $telefone;
    public $email;
    public $endereco_id;

    public function __construct( $id, $nome, $descricao, $telefone, $email, $endereco_id)
    {
        $this->id=$id;
        $this->nome=$nome;
        $this->descricao=$descricao;
        $this->telefone=$telefone;
        $this->email=$email;
        $this->endereco_id=$endereco_id;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getNome() { return $this->nome; }
    public function setNome($nome) {$this->nome = $nome;}

    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) {$this->descricao = $descricao;}

    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) {$this->telefone = $telefone;}

    public function getEmail() { return $this->email; }
    public function setEmail($email) {$this->email = $email;}

    public function getEnderecoId() { return $this->endereco_id; }
    public function setEnderecoId($endereco_id) {$this->endereco_id = $endereco_id;}

    public function getDadosParaJSON() {
        $data = ['id' => $this->id, 'nome' => $this->nome, 'descricao' => $this->descricao, 'telefone' => $this->telefone, 'email' => $this->email, 'endereco_id' => $this->endereco_id];
        return $data;
    }
}
