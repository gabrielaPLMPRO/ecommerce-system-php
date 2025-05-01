<?php include('../includes/header.php'); ?>

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

<?php
require_once '../controllers/FornecedorController.php';
$controllerFornecedores = new FornecedorController();

$fornecedores = new FornecedorController();
$listaFornecedores = $fornecedores->listarTodos();
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Cadastrar Produto</h2>
        <form action="../controllers/ProdutoController.php" method="POST">
            <input type="hidden" name="acao" value="inserir">

            <!-- Fornecedor -->
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" placeholder="Breve descrição" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="fornecedor">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control">
                    <?php
                   

                    foreach ($listaFornecedores as $fornecedor) {
                        echo '<option value="' . htmlspecialchars($fornecedor['id']) . '">' . htmlspecialchars($fornecedor['nome']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
