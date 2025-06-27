<?php

include_once "../fachada.php";
include "../includes/verifica.php";

$id = @$_GET["id"];

$dao = $factory->getUsuarioDao();
$daoCliente = $factory->getClienteDao();

$usuario = $dao->buscaPorId($id);
$cliente = $daoCliente->buscarPorUsuarioId($id);

// $endereco = $dao->buscaPorId($idEndereco);

if($usuario==null) {
    $usuario = new Usuario( null, null, null, null,null);
}

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
        <form action="../controllers/UsuarioController.php" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?=$usuario->getId()?>">

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario->getNome() ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Email</label>
                <input type="text" class="form-control" id="email" name="email" 
                    value="<?= htmlspecialchars($usuario->getEmail() ?? '') ?>" 
                    <?= !empty($usuario->getId()) ? 'disabled' : 'required' ?>>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" value="<?= htmlspecialchars($usuario->getSenha() ?? '') ?>" 
                    <?= !empty($usuario->getId()) ? 'disabled' : 'required' ?>>
            </div>

            <div class="form-group">
            <label for="tipo">Tipo de Usuário</label>
            <select name="tipo" id="tipo" class="form-control" <?= ($usuario->getTipo() === 'admin' &&  $cliente==null)|| $cliente==null?  'disabled' : '' ?>>
                <option value="admin" <?= $usuario->getTipo() === "admin" ? "selected" : '' ?>>Admin</option>
                <option value="cliente" <?= $usuario->getTipo() === "cliente" ? "selected" : '' ?>>Cliente</option>
            </select>

            <?php if (($usuario->getTipo() === 'admin' &&  $cliente==null)|| $cliente==null ): ?>
                <!-- Campo hidden para garantir envio do valor -->
                <input type="hidden" name="tipo" value="admin">
            <?php endif; ?>
        </div>


            
            
            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
