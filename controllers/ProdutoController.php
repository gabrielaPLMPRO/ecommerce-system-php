<?php
require_once '../models/Produto.php';
require_once '../includes/db.connection.php';
require_once '../models/Estoque.php';

class ProdutoController {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function inserir($dados) {
        try {
            $this->conn->beginTransaction();
    
            $produto = new Produto();
            $produto->nome = $dados['nome'];
            $produto->descricao = $dados['descricao'];
            $produto->fornecedor_id = $dados['fornecedor_id'];
            $produto->salvar($this->conn);
    
            $estoque = new Estoque();
            $estoque->produto_id = $produto->id;
            $estoque->preco = isset($dados['preco']) ? $dados['preco'] : 0;
            $estoque->estoque = isset($dados['quantidade']) ? $dados['quantidade'] : 0;
    
            $sql = "INSERT INTO estoque (produto_id, preco, estoque) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$estoque->produto_id, $estoque->preco, $estoque->estoque]);
    
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function consultar($termo) {
        return Produto::buscar($this->conn, $termo);
    }

    public function listarTodos() {
        return Produto::listar($this->conn);
    }
    
    public function alterar($id, $dados) {
        $produto = new Produto();
        $produto->id = $id;
        $produto->nome = $dados['nome'];
        $produto->descricao = $dados['descricao'];
        $produto->fornecedor_id = $dados['fornecedor_id'];
        
        return $produto->atualizar($this->conn);
    }
    public function excluir($id) {
        $produto = new Produto();
        $produto->id=$id; 
        return $produto->excluir($this->conn);
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $controller = new ProdutoController();

    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'inserir':
                if ($controller->inserir($_POST)) {
                    header('Location: ../views/produto_listar.php?msg=inserido');
                } else {
                    header('Location: ../views/produto_listar.php?msg=erro');
                }
                break;

            case 'alterar':
                $controller->alterar($_POST['id'], $_POST);
                header('Location: ../views/produto_listar.php?msg=alterado');
                break;

            case 'excluir':
                if($controller->excluir($_POST['id'])){
                    header('Location: ../views/produto_listar.php?msg=excluido');
                }
                else{
                    header('Location: ../views/produto_listar.php?msg=erro');
                }
                

                break;
        }
    }
}
?>
