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
        </ul>
      </nav>
    </div>
  </header>
