<?php 
include('../includes/header.php'); 
include "../includes/verifica.php";
?>

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

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../includes/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Cadastrar Usuário</h2>
        <form action="../controllers/LoginController.php" method="POST">
            <input type="hidden" name="acao" value="inserir">

            <!-- Fornecedor -->
            <div class="form-group">
                <label for="nome">Nome do Usuário</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="email@exemplo.com" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Usuário</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="cliente">Cliente</option>
                    <option value="admin">Admin</option>
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
