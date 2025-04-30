<?php
require_once '../controllers/FornecedorController.php';
$controller = new FornecedorController();

if (!isset($_GET['id'])) {
    header('Location: fornecedor_listar.php?msg=erro');
    exit;
}

$fornecedor = $controller->consultar($_GET['id']);
if (!$fornecedor) {
    header('Location: fornecedor_listar.php?msg=erro');
    exit;
}

$fornecedor = $fornecedor[0];
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
    <?php if (isset($_GET['msg'])): ?>
        <?php
        $msg = $_GET['msg'];
        $mensagens = [
            'inserido' => 'Fornecedor inserido com sucesso!',
            'alterado' => 'Fornecedor alterado com sucesso!',
            'excluido' => 'Fornecedor excluído com sucesso!',
            'erro' => 'Erro ao realizar a operação!',
        ];
        $mensagem = $mensagens[$msg] ?? '';
        $classeAlerta = in_array($msg, ['inserido', 'alterado', 'excluido']) ? 'alert-success' : 'alert-danger';
        ?>
        <?php if ($mensagem): ?>
            <div class="alert <?= $classeAlerta ?> text-center mx-auto" style="max-width: 600px;" role="alert">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>


<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../includes/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Editar Fornecedor</h2>
        <form action="../controllers/FornecedorController.php" method="POST">
            <input type="hidden" name="acao" value="alterar">
            <input type="hidden" name="id" value="<?= $fornecedor['id'] ?>">
            <input type="hidden" name="endereco_id" value="<?= $fornecedor['endereco_id'] ?>">

            <!-- Dados do Fornecedor -->
            <div class="form-group">
                <label for="nome">Nome do Fornecedor</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($fornecedor['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($fornecedor['descricao']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($fornecedor['telefone']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($fornecedor['email']) ?>" required>
            </div>

            <hr>

            <!-- Endereço -->
            <h5 class="text-center mb-3">Endereço do Fornecedor</h5>

            <div class="form-group">
                <label for="rua">Rua</label>
                <input type="text" class="form-control" id="rua" name="rua" value="<?= htmlspecialchars($fornecedor['rua']) ?>" required>
            </div>

            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($fornecedor['numero']) ?>" required>
            </div>

            <div class="form-group">
                <label for="complemento">Complemento</label>
                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($fornecedor['complemento']) ?>">
            </div>

            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($fornecedor['bairro']) ?>" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($fornecedor['cidade']) ?>" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" value="<?= htmlspecialchars($fornecedor['estado']) ?>" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($fornecedor['cep']) ?>" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
