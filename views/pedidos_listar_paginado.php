<!DOCTYPE html>
<html>
<?php 
include('../includes/header.php'); 
include "../includes/verifica.php";
?>

<head>
  <title>Consulta de Pedidos</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <div class="container mt-4">
    <h3 class="text-center">Consulta de Pedidos - Administração</h3>

    <div class="card">
      <div class="card-header">Buscar Pedidos</div>
      <div class="card-body">
        <input type="text" id="search_box" class="form-control" placeholder="Buscar por número do pedido ou nome do cliente">
        <div id="dynamic_content" class="mt-3"></div>
      </div>
    </div>
  </div>
</body>
</html>

<script>
$(document).ready(function(){

  function load_data(page, query = '') {
    $.ajax({
      url: '../controllers/PedidoController.php',
      method: 'POST',
      data: { page: page, query: query, acao: 'carregar' },
      success: function(data) {
        $('#dynamic_content').html(data);
      }
    });
  }

  load_data(1);

  $('#search_box').on('keyup', function() {
    var query = $(this).val();
    load_data(1, query);
  });

  $(document).on('click', '.page-link', function() {
    var page = $(this).data('page_number');
    var query = $('#search_box').val();
    load_data(page, query);
  });

  // Atualizar status do pedido via AJAX
  $(document).on('change', '.status-select', function() {
    let pedidoId = $(this).data('id');
    let status = $(this).val();
    let dataEnvio = prompt("Informe a data de envio (YYYY-MM-DD), se aplicável:");
    let dataCancelamento = prompt("Informe a data de cancelamento (YYYY-MM-DD), se aplicável:");
    
    $.post('../controllers/PedidoController.php', {
      acao: 'atualizar_status',
      id: pedidoId,
      status: status,
      data_envio: dataEnvio,
      data_cancelamento: dataCancelamento
    }, function(response) {
      alert(response);
      load_data(1, $('#search_box').val());
    });
  });

});
</script>
