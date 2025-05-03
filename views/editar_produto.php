<?php
include "../includes/verifica.php";
require_once '../controllers/ProdutoController.php';
require_once '../controllers/FornecedorController.php';
$controller = new ProdutoController();
$controllerFornecedores = new FornecedorController();

if (!isset($_GET['id'])) {
    header('Location: produto_listar.php?msg=erro');
    exit;
}

$produto = $controller->consultar($_GET['id']);
if (!$produto) {
    header('Location: produto_listar.php?msg=erro');
    exit;
}

$fornecedores = new FornecedorController();
$listaFornecedores = $fornecedores->listarTodos();

$produto = $produto[0];
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
    <?php if (isset($_GET['msg'])): ?>
        <?php
        $msg = $_GET['msg'];
        $mensagens = [
            'inserido' => 'Produto inserido com sucesso!',
            'alterado' => 'Produto alterado com sucesso!',
            'excluido' => 'Produto excluído com sucesso!',
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
        <h2>Editar Produto</h2>
        <form action="../controllers/ProdutoController.php" method="POST">
            <input type="hidden" name="acao" value="alterar">
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="fornecedor">Selecione o Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control">
                    <?php
                   

                    foreach ($listaFornecedores as $fornecedor) {
                        $selected=($fornecedor['fornecedor_id']==$produto['fornecedor_id'])?'selected':'';
                        echo '<option value="' . htmlspecialchars($fornecedor['fornecedor_id']) .'" '. $selected.'>' . htmlspecialchars($fornecedor['nome']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
