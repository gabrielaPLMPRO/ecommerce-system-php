<?php
require_once '../controllers/EstoqueController.php';

$estoqueController = new EstoqueController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    $estoqueController->atualizar($produto_id, $preco, $estoque);
}
?>
