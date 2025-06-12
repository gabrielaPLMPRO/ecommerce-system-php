<?php
include_once "../fachada.php";

$dao = $factory->getProdutoDao();
$daoFornecedor = $factory->getFornecedorDao();
$daoEstoque = $factory->getEstoqueDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'salvar':
                $id = @$_POST["id"];
                $nome = @$_POST["nome"];
                $descricao = @$_POST["descricao"];
                $fornecedor_id = @$_POST["fornecedor_id"];
                $preco = @$_POST["preco"]; 
                $qtdEstoque = @$_POST["quantidade"]; 

                $produto = $dao->buscaPorId($id);

                if ($produto === null) {
                    $produto = new Produto($id, $nome, $descricao, $fornecedor_id);
                    $idProdutoInserido = $dao->insere($produto);
                    if ($idProdutoInserido !== false) {
                        $estoque = new Estoque(0, $preco, $qtdEstoque, $idProdutoInserido);
                        if ($daoEstoque->insere($estoque)) {
                            header('Location: ../views/produto_listar_paginado.php?msg=inserido');
                        } else {
                            header('Location: ../views/produto_listar_paginado.php?msg=erro');
                        }
                    } else {
                        header('Location: ../views/produto_listar_paginado.php?msg=erro');
                    }
                } else {
                    $produto->setNome($nome);
                    $produto->setDescricao($descricao);
                    $produto->setFornecedorId($fornecedor_id);
                    if ($dao->altera($produto)) {
                        header('Location: ../views/produto_listar_paginado.php?msg=alterado');
                    } else {
                        header('Location: ../views/produto_listar_paginado.php?msg=erro');
                    }
                }
                break;

            case 'excluir':
                $idExcluir = @$_POST["idExcluir"];
                $dao->removePorId($idExcluir);
                header('Location: ../views/produto_listar_paginado.php?msg=excluido');
                break;

            case 'carregar':
                $nome = $_POST['query'];
                $produtos = $dao->buscaProdutosComEstoquePaginado();
                $total_data = $dao->contaComNome($nome);

                $output = '
                <label>Quantidade de Registros - '.$total_data.'</label>
                <table class="table table-striped table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Fornecedor</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
                ';

                if ($total_data > 0) {
                    foreach ($produtos as $produto) {
                        $fornecedor = $daoFornecedor->buscaPorId($produto->getFornecedorId());
                        $preco = number_format($produto->getPreco(), 2, ',', '.');
                        $estoque = $produto->getEstoque();

                        $botao = $estoque === 0
                            ? '<button class="btn btn-secondary btn-sm" disabled>Indisponível</button>'
                            : '<button class="btn btn-primary btn-sm">Adicionar ao carrinho</button>';

                        $output .= '
                        <tr>
                            <td>'.$produto->getId().'</td>
                            <td>'.$produto->getNome().'</td>
                            <td>'.$produto->getDescricao().'</td>
                            <td>'.$fornecedor->getNome().'</td>
                            <td>R$ '.$preco.'</td>
                            <td>'.$estoque.'</td>
                            <td>
                                '.$botao.'
                                <a href="editar_produto.php?id='.$produto->getId().'" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../controllers/ProdutoController.php" method="POST" style="display:inline;"
                                    onsubmit="return confirm(\'Tem certeza que deseja excluir este produto?\');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="idExcluir" value="'.$produto->getId().'">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        ';
                    }
                } else {
                    $output .= '
                    <tr>
                        <td colspan="7" align="center">Nenhum produto encontrado</td>
                    </tr>
                    ';
                }

                $output .= '</table>';
                echo $output;
                break;
        }
    }
}
?>
