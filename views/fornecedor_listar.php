<?php
require_once '../controllers/FornecedorController.php';
$controller = new FornecedorController();

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $fornecedores = $controller->consultar($_GET['busca']);
} else {
    $fornecedores = $controller->listarTodos();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
    <h2>Fornecedores</h2>

    <form method="GET" class="form-inline mb-3">
        <input type="text" name="busca" class="form-control mr-2" placeholder="Buscar por nome ou ID" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        <button type="submit" class="btn btn-custom">Buscar</button>
    </form>

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
        <div class="alert <?= $classeAlerta ?>" role="alert">
            <?= $mensagem ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($fornecedores): ?>
                <?php foreach ($fornecedores as $f): ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= htmlspecialchars($f['nome']) ?></td>
                        <td><?= htmlspecialchars($f['email']) ?></td>
                        <td><?= htmlspecialchars($f['telefone']) ?></td>
                        <td><?= htmlspecialchars($f['cidade']) ?></td>
                        <td>
                            <a href="editar_fornecedor.php?id=<?= $f['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <form action="../controllers/FornecedorController.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $f['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Nenhum fornecedor encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>