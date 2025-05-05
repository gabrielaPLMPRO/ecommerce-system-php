<?php
 include('../includes/header.php'); 
 include "../includes/verifica.php";
 ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>

<header class="admin-header">
  <h2>Painel Administrativo</h2>
</header>

<div class="container dashboard-container">
  <div class="row g-4">

  <div class="col-md-6 col-lg-4 mb-4">
      <a href="produto.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <div class="dashboard-title">Cadastrar Produto</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="fornecedor.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <div class="dashboard-title">Cadastrar Fornecedor</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="estoque/listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <div class="dashboard-title">Manutenção de Estoque</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="fornecedor_listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <div class="dashboard-title">Listar Fornecedores</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="produto_listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <div class="dashboard-title">Listar Produtos</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="usuario_listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <div class="dashboard-title">Listar Usuários</div>
        </div>
      </a>
    </div>

  </div>
</div>

<?php include('../includes/footer.php'); ?>