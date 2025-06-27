<?php
include_once "../fachada.php";

$dao = $factory->getUsuarioDao();
$daoEndereco = $factory->getEnderecoDao();
$daoCliente= $factory-> getClienteDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'salvar':

                $id = @$_POST["id"];
                $nome = @$_POST["nome"];
                $email = @$_POST["email"];
                $senha = @$_POST["senha"];
                $tipo = @$_POST["tipo"];

                $usuario = $dao->buscaPorId($id);

                if($usuario===null) {
                    $usuario= new Usuario($id, $nome, $email, $senha, "admin");

                    if($dao->insere($usuario))
                    {
                        header('Location: ../views/usuario_listar_paginado.php?msg=inserido');
                    } else {
                        header('Location: ../views/usuario_listar_paginado.php?msg=erro');
                    }
                } else {
                    $cliente= $daoCliente->buscarPorUsuarioId($usuario->getId());
                    $usuario->setNome($nome);

                    if(($usuario->getTipo() === 'admin' &&  $cliente==null)|| $cliente==null){
                        $usuario->setTipo($tipo);
                    }
                    else{
                        $usuario->setTipo($tipo);
                    }

                    if ($dao->altera($usuario)) {
                        header('Location: ../views/usuario_listar_paginado.php?msg=alterado');
                    } else {
                        header('Location: ../views/usuario_listar_paginado.php?msg=erro');
                    }
                }
                break;
            case 'inserir':

                //informacoes de usuario/cliente
                $id = 0;
                $nome = @$_POST["nome"];
                $email = @$_POST["email"];
                $senha = @$_POST["senha"];

                // informacoes de endereco
                $rua = @$_POST["rua"]; 
                $numero = @$_POST["numero"]; 
                $complemento = @$_POST["complemento"]; 
                $bairro = @$_POST["bairro"]; 
                $cidade = @$_POST["cidade"]; 
                $estado = @$_POST["estado"]; 
                $cep = @$_POST["cep"]; 

                //informacoes de cliente
                $telefone = @$_POST["telefone"]; 
                $cartao_credito = @$_POST["cartao_credito"]; 

                $endereco= new Endereco(0, $rua, $numero, $complemento, $bairro, $cep, $cidade, $estado);
                $usuario= new Usuario($id, $nome,$email,$senha, 'cliente');

                $idEnderecoInserido=$daoEndereco->insere($endereco);
                $idInserido=$dao->insere($usuario);

                if (!$idInserido ||!$idEnderecoInserido) {
                    header('Location: ../views/cadastro.php?msg=erro');
                } else {
                    $cliente= new Cliente(0,$nome,$telefone,$email,$cartao_credito,$idEnderecoInserido,$idInserido);
                    
                    $idClienteInserido= $daoCliente->insere($cliente); 
                    if(!$idClienteInserido){
                        header('Location: ../views/cadastro.php?msg=erro');
                    }
                    else{
                        session_start();
                        
                        $_SESSION["id_usuario"]=$idInserido; 
                        $_SESSION["nome_usuario"] = stripslashes($usuario->getNome());
                        $_SESSION["tipo"] = stripslashes($usuario->getTipo());

                        $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
            
                        if(!empty($carrinho)){
                            header('Location: ../views/carrinho.php');
                            exit;
                        }

                        header('Location: ../views/index.php');
                    }
                }
                break;
            case 'excluir':
                $idExcluir = @$_POST["idExcluir"];
                $dao->removePorId($idExcluir);
                header('Location: ../views/usuario_listar_paginado.php?msg=excluido');
                break;
            case 'executarLogin': 

                $email=@$_POST["email"];
                $senha=@$_POST["senha"];
                $usuario = $dao->buscarPorLogin($email);

                $autenticou=false; 
                
                if($usuario!=null){
                    if(!strcmp(md5($email.$senha),$usuario->getSenha())){
                        $autenticou=true;
                    }
                }

                if($autenticou){
                    
                    //adicionado pois ja pode ter uma session com as informacoes do carrinho

                    session_start();

                    $_SESSION["id_usuario"]= $usuario->getId(); 
                    $_SESSION["nome_usuario"] = stripslashes($usuario->getNome());
                    $_SESSION["tipo"] = stripslashes($usuario->getTipo());

                    $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
            
                    if(!empty($carrinho)){
                        header('Location: ../views/carrinho.php');
                        exit;
                    }

                    if($usuario->getTipo()==="admin"){
                        header('Location: ../views/indexAdmin.php');
                        exit;
                    }
                    else{
                        header('Location: ../views/index.php');
                        exit;
                    }
                }
                else{
                    header('Location: ../views/login.php?msg=erro');
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

                $usuarios = $dao->buscaComNomePaginado($nome,$start,$limit);
                $total_data = $dao->contaComNome($nome);

                $output = '
                <label>Quantidade de Registros - '.$total_data.'</label>
                <table class="table table-striped table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
                ';
                if($total_data > 0)
                {
                foreach($usuarios as $usuario)
                {
                    $output .= '
                    <tr>
                    <td>'.$usuario->getId().'</td>
                    <td>'.$usuario->getNome().'</td>
                    <td>'.$usuario->getEmail().'</td>
                    <td>'.$usuario->getTipo().'</td>
                    <td>
                                    <a href="editar_usuario.php?id='.$usuario->getId().'"
                                        class="btn btn-warning btn-sm btn-custom-actions" data-toggle="tooltip" title="Editar">
                                        <i class="fas fa-edit icon"></i>
                                    </a>
                                    <form action="../controllers/UsuarioController.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm("Tem certeza que deseja excluir este usuário?");">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="idExcluir" value='.$usuario->getId().'">
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
                    <td colspan="5" align="center">Nenhum nome encontrado</td>
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
