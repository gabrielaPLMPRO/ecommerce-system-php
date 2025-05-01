<?php
require_once '../models/Estoque.php';
require_once '../models/Produto.php';

$busca = $_GET['busca'] ?? '';
$estoques = Estoque::listarComProduto($busca);
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
    <h2>Manutenção de Estoque</h2>

    <?php if (isset($_GET['msg'])): ?>
        <?php
        $msg = $_GET['msg'];
        $mensagens = [
            'inserido' => 'Estoque inserido com sucesso!',
            'alterado' => 'Estoque atualizado com sucesso!',
            'excluido' => 'Estoque excluído com sucesso!',
            'erro' => 'Erro ao realizar a operação!'
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

    <form method="GET" class="form-inline mb-3">
        <input type="text" name="busca" class="form-control mr-2" placeholder="Buscar por nome ou ID do produto" value="<?= htmlspecialchars($busca) ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estoques as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nome']) ?></td>
                    <td>R$ <?= number_format($e['preco'], 2, ',', '.') ?></td>
                    <td><?= $e['estoque'] ?></td>
                    <td>
                        <a href="editar_estoque.php?produto_id=<?= $e['produto_id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
