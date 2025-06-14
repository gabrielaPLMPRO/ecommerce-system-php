<?php
class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $foto;
    public $fornecedor_id;

    public function __construct( $id, $nome, $descricao, $foto, $fornecedor_id)
    {
        $this->id=$id;
        $this->nome=$nome;
        $this->descricao=$descricao;
        $this->fornecedor_id=$fornecedor_id;
        $this->foto=$foto;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getNome() { return $this->nome; }
    public function setNome($nome) {$this->nome = $nome;}

    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) {$this->descricao = $descricao;}

    public function getFoto() { return $this->foto; }
    public function setFoto($foto) {$this->foto = $foto;}

    public function getFornecedorId() { return $this->fornecedor_id; }
    public function setFornecedorId($fornecedor_id) {$this->fornecedor_id = $fornecedor_id;}
}
?>

