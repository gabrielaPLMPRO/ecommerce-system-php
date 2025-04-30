<?php
require_once '../controllers/LoginController.php';
$controller = new LoginController();

if (!isset($_GET['id'])) {
    header('Location: usuario_listar.php?msg=erro');
    exit;
}

$usuario = $controller->consultar($_GET['id']);
if (!$usuario) {
    header('Location: usuario_listar.php?msg=erro');
    exit;
}

$usuario = $usuario[0];
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
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
</div>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../includes/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Editar Usuário</h2>
        <form action="../controllers/LoginController.php" method="POST">
            <input type="hidden" name="acao" value="alterar">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="tipo" name="tipo" value="<?= htmlspecialchars($usuario['senha']) ?>" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?= htmlspecialchars($usuario['tipo']) ?>" required>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
