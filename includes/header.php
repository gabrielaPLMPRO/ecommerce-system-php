<?php
include_once __DIR__ . '/paths.php';
include_once __DIR__ . '/comum.php';

if (is_session_started() === FALSE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquiExpress</title>
  <link rel="icon" href="<?= BASE_URL ?>/assets/images/logo.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
   <style>
      .cart-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 18px;
      }

      .cart-icon a {
        position: relative;
        display: inline-block;
      }

      .cart-count {
        position: absolute;
        top: -8px;
        right: -12px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
      }
    </style>
</head>

<body>
  <header>
  <div class="logo-fixed">
    <a href="<?= BASE_URL ?>/views/index.php" style="display: flex; align-items: center; text-decoration: none;">
      <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo da Loja">
    </a>
  </div>
  <div class="header-container">
    <nav class="main-nav">
      <ul>
        <li><a href="<?= BASE_URL ?>/views/index.php">Home</a></li>
        <?php
        if (isset($_SESSION["nome_usuario"])) {
          echo "<li><span>Você está logado como " . $_SESSION["nome_usuario"];
          echo " <a href='" . BASE_URL . "/views/executa_logout.php'>Logout</a></span></li>";
        } else {
          echo "<li><span><a href='" . BASE_URL . "/views/login.php'>Efetuar Login</a></span></li>";
        }
        ?>
         <li class="cart-icon">
            <a href="<?= BASE_URL ?>/views/carrinho.php" style="text-decoration: none; color: black; position: relative;">
              🛒
              <?php
              $qtdItens = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0;
              if ($qtdItens > 0) {
                echo "<span class='cart-count'>$qtdItens</span>";
              }
              ?>
            </a></li>
      </ul>
    </nav>
  </div>

  <!-- Carrinho de Compras -->
 
</header>
