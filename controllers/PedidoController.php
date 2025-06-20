<?php
include_once "../fachada.php";
include_once '../includes/comum.php';

$pedidoDao = $factory->getPedidoDao();
$itensDao = $factory->getItensPedidoDao();
$clienteDao = $factory->getClienteDao();
$produtoDao = $factory->getProdutoDao();
$estoqueDao= $factory->getEstoqueDao();

switch($_POST['acao']){
    case 'carregar':
        $pagina = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $query = isset($_POST['query']) ? trim($_POST['query']) : '';
        $limite = 5;
        $inicio = ($pagina - 1) * $limite;

        $idUsuarioLogado=$_SESSION["tipo"]==="cliente"? $_SESSION["id_usuario"]:null;

        $pedidos = $pedidoDao->buscaComNomePaginado($query, $inicio, $limite,$idUsuarioLogado);
        $total = $pedidoDao->contaComNome($query,$idUsuarioLogado);


        foreach ($pedidos as $pedido) {
            $cliente = $clienteDao->buscaPorId($pedido->getClienteId()); // Assumindo que você tenha esse método
            ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <strong>Pedido #<?= $pedido->getNumero(); ?></strong> - 
                        <?= $cliente ? $cliente->getNome() : "Cliente ID " . $pedido->getClienteId(); ?> |
                        Data: <?= $pedido->getDataPedido(); ?> |
                        Total: R$ <?= number_format($pedido->getTotal(), 2, ',', '.'); ?>
                    </div>
                    <div <?php echo $_SESSION["tipo"]==="cliente"? 'hidden':''; ?>>
                        <select class="status-select form-control form-control-sm" data-id="<?= $pedido->getId(); ?>">
                            <option value="pendente" <?= $pedido->getStatus() == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="enviado" <?= $pedido->getStatus() == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                            <option value="cancelado" <?= $pedido->getStatus() == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div id="itens_<?= $pedido->getId(); ?>">Carregando itens...</div>
                </div>
            </div>
            <script>
                $.post('../controllers/PedidoController.php', {
                    acao: 'carregar_itens',
                    pedido_id: <?= $pedido->getId(); ?>
                }, function(data) {
                    $('#itens_<?= $pedido->getId(); ?>').html(data);
                });
            </script>
            <?php
        }

        // Paginação
        $paginas = ceil($total / $limite);
        echo '<nav><ul class="pagination">';
        for ($i = 1; $i <= $paginas; $i++) {
            echo '<li class="page-item"><a class="page-link" href="#" data-page_number="' . $i . '">' . $i . '</a></li>';
        }
        echo '</ul></nav>';

        exit;
        break;
    case 'carregar_itens':
        $pedido_id = $_POST['pedido_id'];
        $itens = $itensDao->buscaPorPedidoId($pedido_id);
        ?>

        <!-- Carrossel com todas as fotos dos produtos do pedido -->
       <div id="carouselPedido<?= $pedido_id ?>" class="carousel slide mb-4" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($itens as $index => $item): 
                    $produto = $produtoDao->buscaPorId($item->getProdutoId());
                ?>
                <div class="carousel-item <?= ($index == 0) ? 'active' : '' ?>">
                    <img src="data:image/png;base64,<?= $produto->getFoto(); ?>" class="d-block mx-auto" alt="Produto" style="max-height: 200px;">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?= $produto->getNome(); ?></h5>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Botões personalizados com fundo escuro e setas brancas -->
            <a class="carousel-control-prev" href="#carouselPedido<?= $pedido_id ?>" role="button" data-slide="prev" style="background-color: rgba(0,0,0,0.5); width: 40px; height: 40px; top: 50%; transform: translateY(-50%); border-radius: 50%;">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#carouselPedido<?= $pedido_id ?>" role="button" data-slide="next" style="background-color: rgba(0,0,0,0.5); width: 40px; height: 40px; top: 50%; transform: translateY(-50%); border-radius: 50%;">
                <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                <span class="sr-only">Próximo</span>
            </a>
        </div>


        <!-- Tabela com detalhes dos itens -->
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>V. Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): 
                    $produto = $produtoDao->buscaPorId($item->getProdutoId());
                    ?>
                    <tr>
                        <td>
                            <img src="data:image/png;base64,<?= $produto->getFoto(); ?>" alt="Produto" style="max-width: 50px; max-height: 50px;">
                        </td>
                        <td><?= $produto->getDescricao(); ?></td>
                        <td><?= $item->getQuantidade(); ?></td>
                        <td>R$ <?= number_format($item->getPrecoUnitario(), 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($item->getSubtotal(), 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        exit;
        break;
    case 'atualizar_status':
        $id = $_POST['id'];
        $status = $_POST['status'];
        $data_envio = $_POST['data_envio'] ?? null;
        $data_cancelamento = $_POST['data_cancelamento'] ?? null;

        $pedido = $pedidoDao->buscaPorId($id);
        if (!$pedido) {
            echo "Pedido não encontrado.";
            exit;
        }

        $pedido->setStatus($status);
        if ($status == "enviado" && $data_envio) {
            $pedido->setDataEntrega($data_envio);
        } elseif ($status == "cancelado" && $data_cancelamento) {
            $pedido->setDataEntrega($data_cancelamento); // ou outro campo de cancelamento se tiver
        }
        else{
            $pedido->setDataEntrega(null);
        }

        if ($pedidoDao->altera($pedido)) {
            echo "Status atualizado com sucesso.";
        } else {
            echo "Erro ao atualizar.";
        }
        exit;
        break;
    case 'FinalizarCarrinho':
        try{
           if (!isset($_SESSION['id_usuario'])) {
                echo json_encode(['status' => 'deslogado', 'mensagem' => 'Usuário não autenticado. Faça login para continuar.']);
                exit;
            }
            $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
            
            if(empty($carrinho)){
                throw new Exception('Carrinho vazio!');
            }

            $usuarioId = $_SESSION['id_usuario'];

            $cliente= $clienteDao->buscarPorUsuarioId($usuarioId);

            $clienteId=$cliente->getId();

            $numeroPedido = time(); 
            $dataPedido = date('Y-m-d H:i:s');
            $dataEntrega= date('Y-m-d H:i:s', strtotime('+7 days'));
            $status = 'pendente';
            
            $totalGeral = 0;

            // Calcula o total do pedido
            foreach($carrinho as $item){
                $estoque = $estoqueDao->buscaPorProdutoId($item['produto_id']);
                $produto=$produtoDao->buscaPorId($item['produto_id']);
                if($estoque){
                    if($item['quantidade']>$estoque->getEstoque()){
                        throw new Exception('Quantidade inválida, máximo disponível do produto "'.$produto->getNome().'" é '.$estoque->getEstoque());
                    }
                    $totalGeral += ($estoque->getPreco() * $item['quantidade']);
                }
            }

            // Monta o objeto Pedido
            $pedido = new Pedido(0, $clienteId, $usuarioId, $status, $numeroPedido, $dataPedido, $dataEntrega, $totalGeral);
            $pedidoId = $pedidoDao->insere($pedido);

            if(!$pedidoId){
                throw new Exception('Erro ao salvar o pedido');
            }

            foreach($carrinho as $item){
                $estoque = $estoqueDao->buscaPorProdutoId($item['produto_id']);
                if($estoque){
                    $precoUnitario = $estoque->getPreco();
                    $quantidade = $item['quantidade'];
                    $subtotal = $precoUnitario * $quantidade;

                    $itemPedido = new ItemPedido(null, $pedidoId, $item['produto_id'], $quantidade, $precoUnitario, $subtotal);
                    $itensDao->insere($itemPedido);

                    //subtrai itens do estoque
                    $estoqueDao->alterEstoque($item['produto_id'], $estoque->getEstoque()-$quantidade);
                }
            }

            // Limpar o carrinho
            $_SESSION['carrinho'] = [];

            echo json_encode(['status' => 'ok', 'numero' => $numeroPedido]);
            exit;
        }
        catch(Exception $e){
            echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
        }
        
        break; 
}
?>
