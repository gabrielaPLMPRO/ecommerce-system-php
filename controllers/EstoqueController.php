<?php
require_once __DIR__ . '/../models/Estoque.php';
require_once __DIR__ . '/../models/Produto.php';
require_once  __DIR__ .'/../includes/db.connection.php';

class EstoqueController
{    
    public function listar()
    {
        $busca = $_GET['busca'] ?? '';
        return Estoque::listarComProduto($busca);
    }

    public function editar($produto_id)
    {
        $conn = getConnection();
        $estoque = Estoque::buscarPorProdutoId($conn, $produto_id);
        $produto = Produto::buscarPorId($conn, $produto_id);
        return [$estoque, $produto];
    }

    public function atualizar($produto_id, $preco, $estoque)
    {
        $estoqueObj = new Estoque();
        $estoqueObj->produto_id = $produto_id;
        $estoqueObj->preco = $preco;
        $estoqueObj->estoque = $estoque;

        return $estoqueObj->atualizar();
    }

    public function excluir($produto_id)
    {
        $estoque = new Estoque();
        if ($estoque->excluirPorProdutoId($produto_id)) {
            header('Location: ../views/estoque/listar.php?msg=excluido');
        } else {
            header('Location: ../views/estoque/listar.php?msg=erro');
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $produto_id = $_POST['produto_id'] ?? null;

    if ($acao === 'excluir' && $produto_id !== null) {
        $controller = new EstoqueController();
        $controller->excluir($produto_id);
        exit;
    }

    if ($acao === 'alterar') {
        $preco = $_POST['preco'] ?? null;
        $estoque = $_POST['estoque'] ?? null;

        if ($produto_id && $preco !== null && $estoque !== null) {
            $precoFormatado = str_replace(',', '.', $preco);

            $controller = new EstoqueController();
            try {
                if ($controller->atualizar($produto_id, $precoFormatado, $estoque)) {
                    header('Location: ../views/estoque/listar.php?msg=alterado');
                } else {
                    header('Location: ../views/estoque/editar.php?produto_id=' . $produto_id . '&msg=erro');
                }
            } catch (Exception $e) {
                error_log('Erro ao atualizar estoque: ' . $e->getMessage());
                header('Location: ../views/estoque/editar.php?produto_id=' . $produto_id . '&msg=erro');
            }
            exit;
        } else {
            header('Location: ../views/estoque/listar.php?msg=erro');
            exit;
        }
    }
}