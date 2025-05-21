<?php
include "../includes/verifica.php";
include_once "../fachada.php";
$dao = $factory->getFornecedorDAO();

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $fornecedores = $dao->buscaPorNomeCom($_GET['busca']);
} else {
    $fornecedores = $dao->buscaTodos();
}
?>

<?php include('../includes/header.php'); ?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div class="container mt-4">
    <div class="card-form">
        <h2>Fornecedores</h2>

        <div class="d-flex mb-3">
            <form method="GET" class="form-inline mr-2 d-flex">
                <input type="text" name="busca" class="form-control mr-2" placeholder="Buscar por nome ou ID"
                    value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                <button type="submit" class="btn btn-custom mr-2">Buscar</button>
            </form>
            <a href="editar_fornecedor.php" class="btn-aliexpress">
                <i class="fas fa-plus"></i> Novo Fornecedor
            </a>

        </div>

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

        <div class="table-responsive">
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
                                <td><?= $f['fornecedor_id'] ?></td>
                                <td><?= htmlspecialchars($f['nome']) ?></td>
                                <td><?= htmlspecialchars($f['email']) ?></td>
                                <td><?= htmlspecialchars($f['telefone']) ?></td>
                                <td><?= htmlspecialchars($f['cidade']) ?></td>
                                <td>
                                    <a href="editar_fornecedor.php?id=<?= $f['fornecedor_id'] ?>"
                                        class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit icon"></i>
                                    </a>
                                    <form action="../controllers/FornecedorController.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?= $f['fornecedor_id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm btn-custom-actions"
                                            data-toggle="tooltip" title="Excluir">
                                            <i class="fas fa-trash-alt icon"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhum fornecedor encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>