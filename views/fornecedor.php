<?php include('../includes/header.php'); ?>

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

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../includes/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Cadastrar Fornecedor</h2>
        <form action="../controllers/fornecedorController.php" method="POST">
            <input type="hidden" name="acao" value="inserir">

            <!-- Fornecedor -->
            <div class="form-group">
                <label for="nome">Nome do Fornecedor</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" placeholder="Breve descrição" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(99) 99999-9999" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="email@exemplo.com" required>
            </div>

            <hr>

            <!-- Endereço -->
            <h5 class="text-center mb-3">Endereço do Fornecedor</h5>

            <div class="form-group">
                <label for="rua">Rua</label>
                <input type="text" class="form-control" id="rua" name="rua" placeholder="Digite a rua" required>
            </div>

            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" placeholder="Número da casa/empresa" required>
            </div>

            <div class="form-group">
                <label for="complemento">Complemento</label>
                <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Complemento (opcional)">
            </div>

            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Digite o bairro" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Digite a cidade" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" placeholder="Digite o estado" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" placeholder="XXXXX-XXX" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-custom btn-lg btn-block">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
