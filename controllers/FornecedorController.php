<?php
include_once "../fachada.php";

$dao = $factory->getFornecedorDao();
$daoEndereco = $factory->getEnderecoDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'salvar':

                $id = @$_POST["id"];
                $nome = @$_POST["nome"];
                $descricao = @$_POST["descricao"];
                $telefone = @$_POST["telefone"];
                $email = @$_POST["email"];
                $endereco_id = @$_POST["endereco_id"]; 

                $rua = @$_POST["rua"]; 
                $numero = @$_POST["numero"]; 
                $complemento = @$_POST["complemento"]; 
                $bairro = @$_POST["bairro"]; 
                $cidade = @$_POST["cidade"]; 
                $estado = @$_POST["estado"]; 
                $cep = @$_POST["cep"]; 

                $fornecedor = $dao->buscaPorId($id);

                if($fornecedor===null) {
                    $endereco= new Endereco($id, $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep);

                    $idEnderecoInserido=$daoEndereco->insere($endereco);
                    if ($idEnderecoInserido!==false) {
                        
                        $fornecedor = new Fornecedor( $id, $nome, $descricao, $telefone, $email, $idEnderecoInserido);
                        if ($dao->insere($fornecedor)) {
                            header('Location: ../views/fornecedor_listar_paginado.php?msg=inserido');
                        } else {
                            header('Location: ../views/fornecedor_listar_paginado.php?msg=erro');
                        }

                    } else {
                        header('Location: ../views/fornecedor_listar_paginado.php?msg=erro');
                    }
                } else {
                    $endereco= $daoEndereco->buscaPorId($fornecedor->getEnderecoId());

                    $endereco->setRua($rua);
                    $endereco->setNumero($numero);
                    $endereco->setComplemento($complemento);
                    $endereco->setBairro($bairro);
                    $endereco->setCidade($cidade);
                    $endereco->setEstado($estado);
                    $endereco->setCep($cep);

                    if ($daoEndereco->altera($endereco)) {
                        $fornecedor->setNome($nome);
                        $fornecedor->setDescricao($descricao);
                        $fornecedor->setTelefone($telefone);
                        $fornecedor->setEmail($email);
                        if ($dao->altera($fornecedor)) {
                            header('Location: ../views/fornecedor_listar_paginado.php?msg=alterado');
                        } else {
                            header('Location: ../views/fornecedor_listar_paginado.php?msg=erro');
                        }
                    } else {
                        header('Location: ../views/fornecedor_listar_paginado.php?msg=erro');
                    }
                }
                break;

            case 'excluir':
                $idExcluir = @$_POST["idExcluir"];
                $dao->removePorId($idExcluir);
                header('Location: ../views/fornecedor_listar_paginado.php?msg=excluido');
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

                $fornecedores = $dao->buscaComNomePaginado($nome,$start,$limit);
                $total_data = $dao->contaComNome($nome);

                $output = '
                <label>Quantidade de Registros - '.$total_data.'</label>
                <table class="table table-striped table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Cidade</th>
                    <th>Ações</th>
                </tr>
                ';
                if($total_data > 0)
                {
                foreach($fornecedores as $fornecedor)
                {
                    $endereco=$daoEndereco->buscaPorId($fornecedor->getEnderecoId());

                    $output .= '
                    <tr>
                    <td>'.$fornecedor->getId().'</td>
                    <td>'.$fornecedor->getNome().'</td>
                    <td>'.$fornecedor->getEmail().'</td>
                    <td>'.$fornecedor->getTelefone().'</td>
                    <td>'.$endereco->getCidade().'</td>
                    <td>
                                    <a href="editar_fornecedor.php?id='.$fornecedor->getId().'&idEndereco='.$endereco->getId().'"
                                        class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit icon"></i>
                                    </a>
                                    <form action="../controllers/FornecedorController.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm("Tem certeza que deseja excluir este fornecedor?");">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="idExcluir" value='.$fornecedor->getId().'">
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
                    <td colspan="6" align="center">Nenhum nome encontrado</td>
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
