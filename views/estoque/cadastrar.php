<?php
require_once '../../models/Estoque.php';
require_once '../../models/Produto.php';

$busca = $_GET['busca'] ?? '';
$estoques = Estoque::listarComProduto($busca);
?>

<?php include('../../includes/header.php'); ?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<div class="container mt-4">
    <div class="card-form">
        <h2>Manutenção de Estoque</h2>

        <form method="GET" class="form-inline mb-3">
            <input type="text" name="busca" class="form-control mr-2" placeholder="Buscar por nome ou ID do produto" value="<?= htmlspecialchars($busca) ?>">
            <button type="submit" class="btn btn-custom">Buscar</button>
        </form>

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

        <div class="table-responsive">
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
                                <a href="editar.php?produto_id=<?= $e['produto_id'] ?>" class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit icon"></i>
                                </a>
                                <form action="../../controllers/EstoqueController.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este item de estoque?');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="produto_id" value="<?= $e['produto_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm btn-custom-actions" data-toggle="tooltip" title="Excluir">
                                        <i class="fas fa-trash-alt icon"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
