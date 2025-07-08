<!DOCTYPE html>
<html>
<?php 
include('../includes/header.php'); 
include "../includes/verifica_user.php";
?>

<head>
  <title>Consulta de Pedidos</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Estilo laranja para o modal (inspirado no AliExpress) */
    .modal-header.bg-orange {
      background-color: #ff5a00;
      color: white;
    }
    .modal-header.bg-orange .close {
      color: white;
      opacity: 1;
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <h3 class="text-center">Consulta de Pedidos</h3>

    <div class="card">
      <div class="card-body">
        <input type="text" id="search_box" class="form-control" placeholder="Buscar por número do pedido ou nome do cliente">
        <div id="dynamic_content" class="mt-3"></div>
      </div>
    </div>
  </div>

  <!-- Modal para Alterar Status -->
  <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-orange">
          <h5 class="modal-title" id="statusModalLabel">Alterar Status do Pedido</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="statusText"></p>
          <div class="form-group" id="dateField" style="display:none;">
            <label for="statusDate" id="dateLabel"></label>
            <input type="date" class="form-control" id="statusDate">
          </div>
          <input type="hidden" id="modalPedidoId">
          <input type="hidden" id="modalStatus">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="confirmStatusChange">Confirmar</button>
        </div>
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

  $(document).on('change', '.status-select', function() {
    var pedidoId = $(this).data('id');
    var status = $(this).val();

    $('#modalPedidoId').val(pedidoId);
    $('#modalStatus').val(status);

    if (status === "enviado") {
      $('#statusText').text("Você está marcando este pedido como ENVIADO.");
      $('#dateLabel').text("Data de envio:");
      $('#dateField').show();
    } else if (status === "cancelado") {
      $('#statusText').text("Você está marcando este pedido como CANCELADO.");
      $('#dateLabel').text("Data de cancelamento:");
      $('#dateField').show();
    } else {
      $('#statusText').text("Você está alterando o status do pedido.");
      $('#dateField').hide();
    }

    $('#statusDate').val("");
    $('#statusModal').modal('show');
  });

  $('#confirmStatusChange').click(function () {
    var pedidoId = $('#modalPedidoId').val();
    var status = $('#modalStatus').val();
    var data = $('#statusDate').val();

    var postData = {
      acao: 'atualizar_status',
      id: pedidoId,
      status: status
    };

    if (status === "enviado") {
      postData.data_envio = data;
    } else if (status === "cancelado") {
      postData.data_cancelamento = data;
    }

    $.post('../controllers/PedidoController.php', postData, function (response) {
      Swal.fire({
                    icon: 'success',
                    title: 'Status Atualizado!',
                    text: response,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#statusModal').modal('hide');
                    load_data(1, $('#search_box').val());
                });
      
    });
  });

});
</script>
