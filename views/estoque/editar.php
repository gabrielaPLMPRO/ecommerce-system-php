<?php
require_once '../../controllers/EstoqueController.php';

$estoqueController = new EstoqueController();

if (isset($_GET['produto_id'])) {
    list($estoque, $produto) = $estoqueController->editar($_GET['produto_id']);
} else {
    header('Location: estoque.php?msg=erro');
    exit;
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
            'erro' => 'Erro ao realizar a operação!',
        ];
        $mensagem = $mensagens[$msg] ?? '';
        $classeAlerta = in_array($msg, ['inserido', 'alterado']) ? 'alert-success' : 'alert-danger';
        ?>
        <?php if ($mensagem): ?>
            <div class="alert <?= $classeAlerta ?>" role="alert">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/style.css">

<div class="container">
    <div class="card-form">
        <h2>Editar Estoque</h2>
        <form method="POST" action="../../controllers/EstoqueController.php">
            <input type="hidden" name="acao" value="alterar">
            <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

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

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Atualizar Estoque</button>
            </div>
        </form>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>