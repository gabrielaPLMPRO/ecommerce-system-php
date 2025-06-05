<?php
include_once "../fachada.php";

$dao = $factory->getEstoqueDao();
$daoProduto = $factory->getProdutoDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'salvar':

                $id = @$_POST["id"];
                $produto_id = @$_POST["produto_id"];
                $preco = @$_POST["preco"];
                $qtdEstoque = @$_POST["qtdEstoque"];

                $estoque = $dao->buscaPorId($id);

                if($estoque===null) {
                    header('Location: ../views/estoque/listar_paginado.php?msg=erro');
                } else {
                    $estoque->setPreco($preco);
                    $estoque->setEstoque($qtdEstoque);
                    if ($dao->altera($estoque)) {
                        header('Location: ../views/estoque/listar_paginado.php?msg=alterado');
                    } else {
                        header('Location: ../views/estoque/listar_paginado.php?msg=erro');
                    }
                }
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

                $estoques = $dao->buscaComNomePaginado($nome,$start,$limit);
                $total_data = $dao->contaComNome($nome);

                $output = '
                <label>Quantidade de Registros - '.$total_data.'</label>
                <table class="table table-striped table-bordered">
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
                ';
                if($total_data > 0)
                {
                foreach($estoques as $estoque)
                {
                    $produto=$daoProduto->buscaPorId($estoque->getProdutoId());

                    $output .= '
                    <tr>
                    <td>'.$produto->getNome().'</td>
                    <td>'.$estoque->getPreco().'</td>
                    <td>'.$estoque->getEstoque().'</td>
                    <td>
                        <a href="editar.php?id='.$estoque->getId().'&nomeProduto='.$produto->getNome().'"
                            class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                            <i class="fas fa-edit icon"></i>
                        </a>
                    </td>
                    </tr>
                    ';
                }
                }
                else
                {
                $output .= '
                <tr>
                    <td colspan="4" align="center">Nenhum nome encontrado</td>
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
        }
    }
}
?>
