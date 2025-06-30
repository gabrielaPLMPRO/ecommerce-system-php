<?php

include_once "../fachada.php";
include "../includes/verifica.php";

$id = @$_GET["id"];

$dao = $factory->getProdutoDao();
$daoFornecedor = $factory->getFornecedorDao();

$produto = $dao->buscaPorId($id);
$listaFornecedores= $daoFornecedor->buscarTudo();

// $endereco = $dao->buscaPorId($idEndereco);

if($produto==null) {
    $produto = new Produto( null, null, null, null,null);
}

?>

<?php include('../includes/header.php'); ?>
<style>
.upload-label {
    display: inline-block;
    background-color: #0d6efd; /* cor primária do Bootstrap 5 */
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
    user-select: none;
    border: 1px solid transparent;
}
.upload-label:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

#upload-status {
    margin-top: 6px;
    color: #6c757d; /* cinza do Bootstrap */
    font-size: 0.85rem;
    font-style: italic;
}

.preview-img {
    display: block; /* já exibe quando tiver src */
    margin-top: 12px;
    max-width: 200px;
    border-radius: 6px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.12);
    border: 1px solid #dee2e6; /* cinza claro */
}
</style>
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
if(empty($produto->getId()))
{
    echo '<h5 class="text-center mb-3">Estoque do Produto</h5>

            <div class="form-group">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" class="form-control" id="preco" name="preco" placeholder="Digite o preço" required>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade em estoque</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Digite a quantidade" required>
            </div>';
}
?>
<?php
// Suponha que $fotoBase64 contém a imagem salva, sem prefixo:
$fotoBase64 = $produto->getFoto() ?? '';
// Ajuste o tipo mime se for PNG ou outro, conforme sua imagem.
?>
           <div class="form-group">
                <label class="upload-label" for="upload">Selecionar imagem</label>
                <input type="file" id="upload" accept="image/*" hidden>
                <input type="hidden" name="foto" id="foto" value="<?= htmlspecialchars($fotoBase64) ?>">
                <p id="upload-status"><?= $fotoBase64 ? 'Imagem carregada' : 'Nenhuma imagem selecionada' ?></p>
                <img id="preview" src="<?= $fotoBase64 ? 'data:image/jpeg;base64,' . $fotoBase64 : '' ?>" alt="Preview" class="preview-img" style="<?= $fotoBase64 ? '' : 'display:none' ?>">
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Salvar Alterações</button>
            </div>
        
        </form>
    </div>
</div>
<script>
document.getElementById('upload').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const status = document.getElementById('upload-status');
    const preview = document.getElementById('preview');
    const hiddenInput = document.getElementById('foto');

    if (!file) {
        status.textContent = "Nenhuma imagem selecionada";
        preview.style.display = 'none';
        hiddenInput.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const base64 = e.target.result;
        hiddenInput.value = base64.split(',')[1];

        preview.src = base64;
        preview.style.display = 'block';
        status.textContent = `Imagem selecionada: ${file.name}`;
    };
    reader.readAsDataURL(file);
});
</script>

<?php include('../includes/footer.php'); ?>
