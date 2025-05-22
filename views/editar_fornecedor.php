<?php

include_once "../fachada.php";

$id = @$_GET["id"];
$idEndereco = @$_GET["idEndereco"];

$dao = $factory->getFornecedorDao();
$daoEndereco = $factory->getEnderecoDao();

$fornecedor = $dao->buscaPorId($id);
$endereco= $daoEndereco->buscaPorId($idEndereco);

// $endereco = $dao->buscaPorId($idEndereco);

if($fornecedor==null) {
    $fornecedor = new Fornecedor( null, null, null, null, null, null);
}
if($endereco==null) {
    $endereco = new Endereco(null,null, null, null, null, null, null, null);
}
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
        <h2>Editar Fornecedor</h2>
        <form action="../controllers/FornecedorController.php" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?=$fornecedor->getId()?>">
            <input type="hidden" name="endereco_id" value="<?= $fornecedor->getEnderecoId() ?>">

            <div class="form-group">
                <label for="nome">Nome do Fornecedor</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($fornecedor->getNome() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($fornecedor->getDescricao()  ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($fornecedor->getTelefone()  ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($fornecedor->getEmail()  ?? '') ?>" required>
            </div>

            <h5 class="text-center mb-3">Endereço do Fornecedor</h5>

            <div class="form-group">
                <label for="rua">Rua</label>
                <input type="text" class="form-control" id="rua" name="rua" value="<?= htmlspecialchars($endereco->getRua() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($endereco->getNumero() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="complemento">Complemento</label>
                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($endereco->getComplemento() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($endereco->getBairro() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($endereco->getCidade() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" value="<?= htmlspecialchars($endereco->getEstado() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($endereco->getCep() ?? '') ?>" required>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
