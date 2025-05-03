<?php
 include('../includes/header.php'); 
 include "../includes/verifica.php";
 ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
  .dashboard-container {
    padding: 60px 20px;
  }

  .dashboard-card {
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
    background-color: #ffffff;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
  }

  .dashboard-icon {
    font-size: 40px;
    color: #e60023;
    margin-bottom: 15px;
  }

  .dashboard-title {
    font-size: 1.2rem;
    font-weight: bold;
  }

  .admin-header {
    background-color: #f8f9fa;
    padding: 15px 0;
    margin-bottom: 30px;
    text-align: center;
  }
  
  body {
    background-color: #f5f5f5;
  }
</style>

<header class="admin-header">
  
  <h2>Painel Administrativo</h2>
</header>

<div class="container dashboard-container">
  <div class="row g-4">

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="fornecedor.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="fas fa-truck dashboard-icon"></i>
          <div class="dashboard-title">Cadastrar Fornecedor</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="produto.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="fas fa-box dashboard-icon"></i>
          <div class="dashboard-title">Cadastrar Produto</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="fornecedor_listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="fas fa-list dashboard-icon"></i>
          <div class="dashboard-title">Listar Fornecedores</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="produto_listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="fas fa-boxes dashboard-icon"></i>
          <div class="dashboard-title">Listar Produtos</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="estoque.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="fas fa-warehouse dashboard-icon"></i>
          <div class="dashboard-title">Gerenciar Estoque</div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <a href="usuario_listar.php" class="text-decoration-none text-dark">
        <div class="dashboard-card">
          <i class="fas fa-users dashboard-icon"></i>
          <div class="dashboard-title">Listar Usuários</div>
        </div>
      </a>
    </div>

  </div>
</div>

<?php include('../includes/footer.php'); ?>