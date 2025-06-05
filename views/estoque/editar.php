<?php

include_once "../../fachada.php";
include "../../includes/verifica.php";


$id = @$_GET["id"];
$nomeProduto = @$_GET["nomeProduto"];

$dao = $factory->getEstoqueDao();

$estoque = $dao->buscaPorId($id);

if($estoque==null) {
    header("Location: ../views/estoque/listar_paginado.php?msg=erro");
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-4">
    <?php if (isset($_GET['msg'])): ?>
        <?php
        $msg = $_GET['msg'];
        $mensagens = [
            'inserido' => 'Estoque inserido com sucesso!',
            'alterado' => 'Estoque alterado com sucesso!',
            'excluido' => 'Estoque excluído com sucesso!',
            'erro' => 'Erro ao realizar a operação!',
        ];
        $mensagem = $mensagens[$msg] ?? '';
        $classeAlerta = in_array($msg, ['inserido', 'alterado', 'excluido']) ? 'alert-success' : 'alert-danger';
        ?>
        <?php if ($mensagem): ?>
            <div class="alert <?= $classeAlerta ?>" role="alert">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../includes/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Editar Estoque</h2>
        <form action="../../controllers/EstoqueController.php" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?=$estoque->getId()?>">
            <input type="hidden" name="produto_id" value="<?=$estoque->getProdutoId()?>">

            <div class="form-group">
                <label for="nome">Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nomeProduto ?? '') ?>" readOnly>
            </div>

            <div class="form-group">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?= number_format($estoque->getPreco(), 2) ?>" required>
            </div>

            <div class="form-group">
                <label for="qtdEstoque">Quantidade em Estoque</label>
                <input type="number" class="form-control" id="qtdEstoque" name="qtdEstoque" value="<?= $estoque->getEstoque() ?>" required>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        
        </form>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>
