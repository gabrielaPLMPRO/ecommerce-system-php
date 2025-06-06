<?php
require_once '../controllers/LoginController.php';
include "../includes/verifica.php";
$controller = new LoginController();

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $usuarios = $controller->consultar($_GET['busca']);
} else {
    $usuarios = $controller->listarTodos();
}
?>

<?php include('../includes/header.php'); ?>

<!-- Adicionando a CDN do Font Awesome -->
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div class="container mt-4">
    <div class="card-form">
        <h2>Usuários</h2>

        <!-- Formulário de Busca -->
        <form method="GET" class="form-inline mb-3">
            <input type="text" name="busca" class="form-control mr-2" placeholder="Buscar por nome ou ID" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
            <button type="submit" class="btn btn-custom">Buscar</button>
        </form>
        <a href="usuario.php" class="btn-aliexpress">
            <i class="fas fa-plus"></i> Novo Usuário
        </a>

        <?php if (isset($_GET['msg'])): ?>
        <?php
        $msg = $_GET['msg'];
        $mensagens = [
            'inserido' => 'Usuário inserido com sucesso!',
            'alterado' => 'Usuário alterado com sucesso!',
            'excluido' => 'Usuário excluído com sucesso!',
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
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($usuarios): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['tipo']) ?></td>
                                <td>
                                    <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit icon"></i>
                                    </a>
                                    <form action="../controllers/LoginController.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm btn-custom-actions" data-toggle="tooltip" title="Excluir">
                                            <i class="fas fa-trash-alt icon"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Nenhum usuário encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>


