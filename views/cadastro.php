<?php include 'includes/header.php'; ?>

<h2>Cadastro de Usuário</h2>

<form action="cadastrar.php" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" required><br>

    <label for="tipo">Tipo:</label>
    <select name="tipo" required>
        <option value="cliente">Cliente</option>
        <option value="admin">Administrador</option>
    </select><br>

    <input type="submit" value="Cadastrar">
</form>

<?php include 'includes/footer.php'; ?>
