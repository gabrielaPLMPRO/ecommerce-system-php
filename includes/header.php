<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Virtual</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/logo.png" type="image/png">
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <a href="index.php"><img src="assets/images/logo.png" alt="Logo da Loja Virtual"></a>
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="products.php">Produtos</a></li>
                <li><a href="about.php">Sobre</a></li>
                <li><a href="contact.php">Contato</a></li>
                <?php
                session_start();
                if(isset($_SESSION['user_logged_in'])) {
                    echo '<li><a href="logout.php">Sair</a></li>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="register.php">Cadastrar</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>
