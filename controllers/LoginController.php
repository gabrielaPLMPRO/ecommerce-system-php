<?php
require_once '../models/Usuario.php';
require_once '../includes/db.connection.php';

class LoginController {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function inserir($dados) {
        try {
            // Iniciar transação
            $this->conn->beginTransaction();

            $usuario = new Usuario();
            $usuario->nome = $dados['nome'];
            $usuario->email = $dados['email'];
            $usuario->senha = md5($dados['email'].$dados['senha']);
            if(!$dados['tipo']==null){
                $usuario->tipo=$dados['tipo'];
            }

            $usuario->salvar($this->conn);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function login($email, $dados) {
        session_start();

        try {
            // Iniciar transação
            $this->conn->beginTransaction();

            $usuario = new Usuario();

            $usuarioBuscado = $usuario->buscaPorLogin($this->conn, $email);

            if(!$usuarioBuscado){
                return false; 
            }
            else{
                if(!strcmp(md5($dados['email'].$dados['senha']),$usuarioBuscado->senha)){
                    $_SESSION["id_usuario"]= $usuarioBuscado->id; 
                    $_SESSION["nome_usuario"] = stripslashes($usuarioBuscado->nome);
                    $_SESSION["tipo"] = stripslashes($usuarioBuscado->tipo);

                    return true;
                }
                else{
                    return false;
                }
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function consultar($termo) {
        return Usuario::buscar($this->conn, $termo);
    }

    public function listarTodos() {
        return Usuario::listar($this->conn);
    }
    
    public function alterar($id, $dados) {
        $usuario = new Usuario();
        $usuario->id = $id;
        $usuario->nome = $dados['nome'];
        $usuario->email = $dados['email'];
        $usuario->senha = md5($dados['email'].$dados['senha']);
        $usuario->tipo = $dados['tipo'];
        
        return $usuario->atualizar($this->conn);
    }
    public function excluir($id) {
        $usuario = new Usuario();
        $usuario->id=$id; 
        return $usuario->excluir($this->conn);
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $controller = new LoginController();

    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'inserir':
                if ($controller->inserir($_POST)) {
                    header('Location: ../views/cadastro.php?msg=inserido');
                } else {
                    header('Location: ../views/cadastro.php?msg=erro');
                }
                break;

            case 'executarLogin': 
                if ($controller->login($_POST['email'], $_POST)) {
                    if($_SESSION["tipo"]==="admin"){
                        header('Location: ../views/indexAdmin.php');
                    }
                    else{
                        header('Location: ../views/index.php');
                    }
                } else {
                    header('Location: ../views/login.php?msg=erro');
                }
                break; 

            case 'alterar':
                $controller->alterar($_POST['id'], $_POST);
                header('Location: ../views/usuario_listar.php?msg=alterado');
                break;

            case 'excluir':
                if($controller->excluir($_POST['id'])){
                    header('Location: ../views/usuario_listar.php?msg=excluido');
                }
                else{
                    header('Location: ../views/usuario_listar.php?msg=erro');
                }
                

                break;
        }
    }
}
?>
