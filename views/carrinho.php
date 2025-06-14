<!DOCTYPE html>
<html>
<?php 
include('../includes/header.php'); 
include_once "../fachada.php";

$daoProduto =  $factory->getProdutoDao();
$daoEstoque = $factory->getEstoqueDao();

$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
?>

<head>
    <title>Meu Carrinho</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }
        .product-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .product-image {
            max-height: 120px;
            object-fit: cover;
        }
        .btn-aliexpress {
            background-color: #ff4500;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .btn-aliexpress:hover {
            background-color: #e03e00;
        }
    </style>
</head>

<body>
    <br />
    <div class="container">
        <h3 align="center">Meu Carrinho</h3>
        <br />
        <div class="card">
            <div class="card-header">Itens do Carrinho</div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $totalGeral = 0;

                    if (!empty($carrinho)) {
                        foreach ($carrinho as $item) {
                            $produto = $daoProduto->buscaPorId($item['produto_id']);
                            $estoque = $daoEstoque->buscaProProdutoId($item['produto_id']);

                            if ($produto && $estoque) {
                                $subtotal = $estoque->getPreco() * $item['quantidade'];
                                $totalGeral += $subtotal;
                                ?>
                                <div class="col-md-4">
                                    <div class="product-card text-center">
                                        <img src="data:image/png;base64,<?= $produto->getFoto(); ?>" class="img-fluid product-image mb-2">
                                        <h5><?= $produto->getNome(); ?></h5>
                                        <p><?= substr($produto->getDescricao(), 0, 60); ?>...</p>
                                        <p><strong>Preço:</strong> R$ <?= number_format($estoque->getPreco(), 2, ',', '.'); ?></p>
                                        <p><strong>Qtd:</strong> <?= $item['quantidade']; ?></p>
                                        <p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.'); ?></p>
                                        <button class="btn btn-success btn-sm adicionar-item" data-id="<?= $produto->getId(); ?>">
                                            <i class="fas fa-plus"></i> Adicionar Mais
                                        </button>
                                        <button class="btn-aliexpress remover-item" data-id="<?= $produto->getId(); ?>">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="col-12 mt-3">
                            <h5>Total Geral: R$ <?= number_format($totalGeral, 2, ',', '.'); ?></h5>
                            <button class="btn btn-success">Finalizar Compra</button>
                        </div>
                        <?php
                    } else {
                        echo '<div class="col-12"><p>O carrinho está vazio.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.remover-item').click(function(){
                var produtoId = $(this).data('id');

                $.ajax({
                    url: '../controllers/ProdutoController.php',
                    method: 'POST',
                    data: { acao: 'RemoverDoCarrinho', produto_id: produtoId },
                    success: function(response){
                        Swal.fire({
                            icon: 'success',
                            title: 'Removido!',
                            text: 'O produto foi removido do carrinho.',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            $('.adicionar-item').click(function(){
                var produtoId = $(this).data('id');

                $.ajax({
                    url: '../controllers/ProdutoController.php',
                    method: 'POST',
                    data: { acao: 'AdicionarMaisCarrinho', produto_id: produtoId },
                    success: function(response){
                        Swal.fire({
                            icon: 'success',
                            title: 'Adicionado!',
                            text: 'Mais uma unidade foi adicionada ao carrinho.',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>
