<?php include 'includes/header.php'; ?>

<h2>Login</h2>

<form action="login.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" required><br>

    <input type="submit" value="Entrar">
</form>

<?php include 'includes/footer.php'; ?>
