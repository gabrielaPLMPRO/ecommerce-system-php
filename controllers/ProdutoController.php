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
                $foto = @$_POST["foto"];
                $fornecedor_id = @$_POST["fornecedor_id"];

                $preco = @$_POST["preco"]; 
                $qtdEstoque = @$_POST["quantidade"]; 

                $produto = $dao->buscaPorId($id);

                if($produto===null) {
                    $produto= new Produto($id, $nome, $descricao, $foto, $fornecedor_id);

                    $idProdutoInserido=$dao->insere($produto);
                    if ($idProdutoInserido!==false) {
                        
                        $estoque = new Estoque( 0, $preco, $qtdEstoque,$idProdutoInserido);
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
                    $produto->setFoto($foto);
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
                            
                $limit = '5';
                $page = 1;
                if($_POST['page'] > 1)
                {
                $start = (($_POST['page'] - 1) * $limit);
                $page = $_POST['page'];
                }
                else
                {
                $start = 0;
                }

                $produtos = $dao->buscaComNomePaginado($nome,$start,$limit);
                $total_data = $dao->contaComNome($nome);

                $output = '
                <label>Quantidade de Registros - '.$total_data.'</label>
                <table class="table table-striped table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Fornecedor</th>
                    <th>Ações</th>
                </tr>
                ';
                if($total_data > 0)
                {
                foreach($produtos as $produto)
                {
                    $fornecedor=$daoFornecedor->buscaPorId($produto->getFornecedorId());

                    $output .= '
                    <tr>
                    <td>'.$produto->getId().'</td>
                    <td>'.$produto->getNome().'</td>
                    <td>'.$produto->getDescricao().'</td>
                    <td>'.$fornecedor->getNome().'</td>
                    <td>
                                    <a href="editar_produto.php?id='.$produto->getId().'"
                                        class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit icon"></i>
                                    </a>
                                    <form action="../controllers/ProdutoController.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm("Tem certeza que deseja excluir este produto?");">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="idExcluir" value='.$produto->getId().'">
                                        <button type="submit" class="btn btn-danger btn-sm btn-custom-actions"
                                            data-toggle="tooltip" title="Excluir">
                                            <i class="fas fa-trash-alt icon"></i>
                                        </button>
                                    </form>
                                </td>
                    </tr>
                    ';
                }
                }
                else
                {
                $output .= '
                <tr>
                    <td colspan="5" align="center">Nenhum produto encontrado</td>
                </tr>
                ';
                }

                $output .= '
                </table>
                <br />
                <div align="center">
                <ul class="pagination">
                ';

                $total_links = ceil($total_data/$limit);
                $previous_link = '';
                $next_link = '';
                $page_link = '';
                $page_array = [];

                if($total_links > 4)
                {
                if($page < 5)
                {
                    for($count = 1; $count <= 5; $count++)
                    {
                    $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                }
                else
                {
                    $end_limit = $total_links - 5;
                    if($page > $end_limit)
                    {
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $end_limit; $count <= $total_links; $count++)
                    {
                        $page_array[] = $count;
                    }
                    }
                    else
                    {
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $page - 1; $count <= $page + 1; $count++)
                    {
                        $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                    }
                }
                }
                else
                {
                for($count = 1; $count <= $total_links; $count++)
                {
                    $page_array[] = $count;
                }
                }

                for($count = 0; $count < count($page_array); $count++)
                {
                if($page == $page_array[$count])
                {
                    $page_link .= '
                    <li class="page-item active">
                    <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                    </li>
                    ';

                    $previous_id = $page_array[$count] - 1;
                    if($previous_id > 0)
                    {
                    $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Anterior</a></li>';
                    }
                    else
                    {
                    $previous_link = '
                    <li class="page-item disabled">
                        <a class="page-link" href="#">Anterior</a>
                    </li>
                    ';
                    }
                    $next_id = $page_array[$count] + 1;
                    if($next_id > $total_links)
                    {
                    $next_link = '
                    <li class="page-item disabled">
                        <a class="page-link" href="#">Próximo</a>
                    </li>
                        ';
                    }
                    else
                    {
                    $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Próximo</a></li>';
                    }
                }
                else
                {
                    if($page_array[$count] == '...')
                    {
                    $page_link .= '
                    <li class="page-item disabled">
                        <a class="page-link" href="#">...</a>
                    </li>
                    ';
                    }
                    else
                    {
                    $page_link .= '
                    <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
                    ';
                    }
                }
                }

                $output .= $previous_link . $page_link . $next_link;
                $output .= '
                </ul>

                </div>
                ';

                echo $output;
                break;
            
            case 'carregarCatalogo': 

                $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
                $query = isset($_POST['query']) ? $_POST['query'] : '';

                $produtosPorPagina = 6;
                $inicio = ($page - 1) * $produtosPorPagina;

                // Simulação de dados (substitua pelo seu banco)
                $nome = $_POST['query'];
                            
                $limit = '5';
                $page = 1;
                if($_POST['page'] > 1)
                {
                $start = (($_POST['page'] - 1) * $limit);
                $page = $_POST['page'];
                }
                else
                {
                $start = 0;
                }

                $todosProdutos = $dao->buscarTodos($nome);

                $produtosPaginados = array_slice($todosProdutos, $inicio, $produtosPorPagina);

                foreach ($produtosPaginados as $produto) {
                    echo '<div class="col-md-4">';
                    echo '<div class="product-card">';
                    echo '<img src="data:image/png;base64,' . $produto->getFoto() . '" alt="Foto do Produto">';
                    echo '<h5>'.$produto->getNome().'</h5>';
                    echo '<p>'.substr($produto->getDescricao(), 0, 80).'...</p>';
                    echo '</div>';
                    echo '</div>';
                }

                // Paginação
                $totalPaginas = ceil(count($todosProdutos) / $produtosPorPagina);
                if ($totalPaginas > 1) {
                    echo '<div class="col-12"><nav><ul class="pagination justify-content-center">';
                    for ($i = 1; $i <= $totalPaginas; $i++) {
                        echo '<li class="page-item"><a class="page-link" data-page_number="'.$i.'" href="#">'.$i.'</a></li>';
                    }
                    echo '</ul></nav></div>';
                }
                break; 
        }
    }
}
?>
