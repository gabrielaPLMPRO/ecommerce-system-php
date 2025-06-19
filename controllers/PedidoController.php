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
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><img src="../img/produtos/<?= $item->getProdutoId(); ?>.jpg" width="50"></td>
                    <td><?= $produtoDao->buscaPorId($item->getProdutoId())->getDescricao(); ?></td>
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
        $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
        
        if(empty($carrinho)){
            echo json_encode(['status' => 'erro', 'mensagem' => 'Carrinho vazio!']);
            exit;
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
            if($estoque){
                $totalGeral += ($estoque->getPreco() * $item['quantidade']);
            }
        }

        // Monta o objeto Pedido
        $pedido = new Pedido(0, $clienteId, $usuarioId, $status, $numeroPedido, $dataPedido, $dataEntrega, $totalGeral);
        $pedidoId = $pedidoDao->insere($pedido);

        if(!$pedidoId){
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar o pedido']);
            exit;
        }

        foreach($carrinho as $item){
            $estoque = $estoqueDao->buscaPorProdutoId($item['produto_id']);
            if($estoque){
                $precoUnitario = $estoque->getPreco();
                $quantidade = $item['quantidade'];
                $subtotal = $precoUnitario * $quantidade;

                $itemPedido = new ItemPedido(null, $pedidoId, $item['produto_id'], $quantidade, $precoUnitario, $subtotal);
                $itensDao->insere($itemPedido);
            }
        }

        // Limpar o carrinho
        $_SESSION['carrinho'] = [];

        echo json_encode(['status' => 'ok', 'numero' => $numeroPedido]);
        exit;
        break; 
}
?>
