<?php

include_once "../fachada.php";

$id = @$_GET["id"];

$dao = $factory->getProdutoDao();

$produto = $dao->buscaPorId($id);

// $endereco = $dao->buscaPorId($idEndereco);

if($produto==null) {
    $produto = new Produto( null, null, null, null);
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
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
</div>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../includes/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Editar Produto</h2>
        <form action="../controllers/ProdutoController.php" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?=$produto->getId()?>">

            <div class="form-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($produto->getNome() ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($produto->getDescricao()  ?? '') ?></textarea>
            </div>

             <div class="form-group">
                <label for="fornecedor">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control">
                    <?php
                    foreach ($listaFornecedores as $fornecedor) {
                        $selected=($fornecedor->getId()==$produto->getFornecedorId())?'selected':'';
                        echo '<option value="' . htmlspecialchars($fornecedor->getId()) .'" '. $selected.'>' . htmlspecialchars($fornecedor->getNome()) . '</option>';
                    }
                    ?>
                </select>
            </div>
<?php 
if(!empty($produto->getId())){
    echo '<h5 class="text-center mb-3">Estoque do Produto</h5>

            <div class="form-group">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" class="form-control" id="preco" name="preco" placeholder="Digite o preço" required>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade em estoque</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Digite a quantidade" required>
            </div>'
}
?>
            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
