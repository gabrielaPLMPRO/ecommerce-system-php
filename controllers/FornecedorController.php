<?php
require_once '../models/Fornecedor.php';
require_once '../models/Endereco.php';
require_once '../includes/db.connection.php';

class FornecedorController {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function inserir($dados) {
        try {
            $this->conn->beginTransaction();

            $endereco = new Endereco();
            $endereco->rua = $dados['rua'];
            $endereco->numero = $dados['numero'];
            $endereco->complemento = $dados['complemento'];
            $endereco->bairro = $dados['bairro'];
            $endereco->cidade = $dados['cidade'];
            $endereco->estado = $dados['estado'];
            $endereco->cep = $dados['cep'];

            $enderecoId = $endereco->salvar($this->conn);

            $fornecedor = new Fornecedor(
                null,
                $dados['nome'],
                $dados['descricao'],
                $dados['telefone'],
                $dados['email'],
                $enderecoId
            );

            $fornecedor->salvar($this->conn);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function alterar($id, $dados) {
        $fornecedor = new Fornecedor(
            $id,
            $dados['nome'],
            $dados['descricao'],
            $dados['telefone'],
            $dados['email'],
            $dados['endereco_id']
        );
        return $fornecedor->atualizar($this->conn);
    }

    public function excluir($id) {
        $fornecedor = new Fornecedor($id);
        return $fornecedor->excluir($this->conn);
    }

    public function consultar($termo) {
        return Fornecedor::buscar($this->conn, $termo);
    }

    public function listarTodos() {
        return Fornecedor::listar($this->conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new FornecedorController();

    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'inserir':
                if ($controller->inserir($_POST)) {
                    header('Location: ../views/fornecedor.php?msg=inserido');
                } else {
                    header('Location: ../views/fornecedor.php?msg=erro');
                }
                break;

                case 'alterar':
                    if ($controller->alterar($_POST['id'], $_POST)) {
                        header('Location: ../views/fornecedor_listar.php?msg=alterado');
                    } else {
                        header('Location: ../views/editar_fornecedor.php?id=' . $_POST['id'] . '&msg=erro');
                    }
                    break;

            case 'excluir':
                $controller->excluir($_POST['id']);
                header('Location: ../views/fornecedor_listar.php?msg=excluido');
                break;
        }
    }
}
?>
