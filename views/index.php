<?php include('../includes/header.php'); ?>


<head>
    <title>Catálogo de Produtos</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            transition: box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
            max-height: 150px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .product-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-aliexpress {
            background-color: #ff4747;
            color: #fff;
            border-radius: 5px;
            padding: 8px 15px;
            text-decoration: none;
        }
        .btn-aliexpress:hover {
            background-color: #ff2e2e;
            color: #fff;
        }
    </style>
</head>
<body>
    <br />
    <div class="container">
        <h3 align="center">Catálogo de Produtos</h3>
        <br />
        <div class="card">
            <div class="card-header">
                <input type="text" name="search_box" id="search_box" class="form-control" placeholder="Buscar por nome do produto..." />
                <br>
            </div>
            <div class="card-body">
                <div id="dynamic_content" class="row"></div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){

        load_data(1);

        function load_data(page, query = '') {
            $.ajax({
                url: "../controllers/ProdutoController.php",
                method: "POST",
                data: { page: page, query: query, acao: 'carregarCatalogo' },
                success: function(data) {
                    $('#dynamic_content').html(data);
                }
            });
        }

        $(document).on('click', '.page-link', function() {
            var page = $(this).data('page_number');
            var query = $('#search_box').val();
            load_data(page, query);
        });

        $('#search_box').keyup(function() {
            var query = $(this).val();
            load_data(1, query);
        });

    });
    </script>
</body>

<?php include('../includes/footer.php'); ?>