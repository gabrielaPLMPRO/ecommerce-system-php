<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Virtual</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="../assets/images/logo.png" alt="Logo da Loja">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Produtos</a></li>
                    <li><a href="#">Sobre</a></li>
                    <li><a href="#">Contato</a></li>
                    <?php	
                    include_once "comum.php";
                    
                    if ( is_session_started() === FALSE ) {
                        session_start();
                    }	
                    
                    if(isset($_SESSION["nome_usuario"])) {
                        // Informações de login
                        echo "<li><span>Você está logado como " . $_SESSION["nome_usuario"];		
                        echo "<a href='executa_logout.php'> Logout </a></span></li>";
                    } else {
                        echo "<li><span><a href='login.php'> Efetuar Login </a></span></li>";
                    }
                    ?>	
                </ul>
            </nav>
               
        </div>
    </header>
