<?php
require_once '../controllers/EstoqueController.php';

$estoqueController = new EstoqueController();

// Verifica se o ID do produto foi passado via GET
if (isset($_GET['produto_id'])) {
    list($estoque, $produto) = $estoqueController->editar($_GET['produto_id']);
} else {
    header('Location: estoque.php?msg=erro');
}

?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
    <h2>Editar Estoque de Produto</h2>
    <form method="POST" action="atualiza_estoque.php">
        <div class="form-group">
            <label for="nome">Produto:</label>
            <input type="text" class="form-control" id="nome" value="<?= htmlspecialchars($produto['nome']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="text" class="form-control" id="preco" name="preco" value="<?= number_format($estoque['preco'], 2, ',', '.') ?>" required>
        </div>

        <div class="form-group">
            <label for="estoque">Quantidade em Estoque:</label>
            <input type="number" class="form-control" id="estoque" name="estoque" value="<?= $estoque['estoque'] ?>" required>
        </div>

        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

        <button type="submit" class="btn btn-success">Atualizar Estoque</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
