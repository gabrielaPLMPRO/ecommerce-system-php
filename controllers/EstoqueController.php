<?php
require_once '../models/Estoque.php';
require_once '../models/Produto.php';

class EstoqueController {

    public function listar() {
        $busca = $_GET['busca'] ?? '';
        $estoques = Estoque::listarComProduto($busca);
        return $estoques;
    }

    public function editar($produto_id) {
        $estoque = Estoque::buscarPorProdutoId($produto_id);
        $produto = Produto::buscarPorId($produto_id);
        return [$estoque, $produto];
    }

    public function atualizar($produto_id, $preco, $estoque) {
        $estoqueObj = new Estoque();
        $estoqueObj->produto_id = $produto_id;
        $estoqueObj->preco = $preco;
        $estoqueObj->estoque = $estoque;
        
        if ($estoqueObj->atualizar()) {
            header('Location: estoque.php?msg=alterado');
        } else {
            header('Location: estoque.php?msg=erro');
        }
    }
    
    public function excluir($produto_id) {
        $estoque = new Estoque();
        $estoque->produto_id = $produto_id;
        if ($estoque->excluir()) {
            header('Location: estoque.php?msg=excluido');
        } else {
            header('Location: estoque.php?msg=erro');
        }
    }
}
?>
