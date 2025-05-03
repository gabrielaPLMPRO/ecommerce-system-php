<?php
include "../includes/verifica.php";
require_once '../controllers/ProdutoController.php';
$controller = new ProdutoController();

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $produtos = $controller->consultar($_GET['busca']);
} else {
    $produtos = $controller->listarTodos();
}
?>

<?php include('../includes/header.php'); ?>

<!-- Adicionando a CDN do Font Awesome -->
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div class="container mt-4">
    <div class="card-form">
        <h2>Produtos</h2>

        <!-- Formulário de Busca -->
        <form method="GET" class="form-inline mb-3">
            <input type="text" name="busca" class="form-control mr-2" placeholder="Buscar por nome, ID ou descrição" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
            <button type="submit" class="btn btn-custom">Buscar</button>
        </form>
        <a href="produto.php" class="btn-aliexpress">
            <i class="fas fa-plus"></i> Novo Produto
        </a>

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

        <!-- Tabela de Fornecedores -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Fornecedor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($produtos): ?>
                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td><?= htmlspecialchars($p['descricao']) ?></td>
                                <td><?= htmlspecialchars($p['fornecedor']) ?></td>
                                <td>
                                    <a href="editar_produto.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit icon"></i>
                                    </a>
                                    <form action="../controllers/ProdutoController.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm btn-custom-actions" data-toggle="tooltip" title="Excluir">
                                            <i class="fas fa-trash-alt icon"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Nenhum produto encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>


