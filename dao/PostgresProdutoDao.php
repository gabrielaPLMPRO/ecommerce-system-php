<?php

include_once('PostgresDao.php');
include_once('../models/ProdutoEstoque.php');

class PostgresProdutoDao extends PostgresDao
{

    private $table_name = 'produtos';

    public function insere($produto)
    {

        $query = "INSERT INTO " . $this->table_name .
            " (nome, descricao, fornecedor_id) VALUES" .
            " (:nome, :descricao, :fornecedor_id)";

        $stmt = $this->conn->prepare($query);

        $nome = $produto->getNome();
        $descricao = $produto->getDescricao();
        $fornecedor_id = $produto->getFornecedorId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":fornecedor_id", $fornecedor_id);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function buscaProdutosComEstoquePaginado(): array
    {
        $produtosEstoque = [];
        $sql = "SELECT p.id, p.nome, p.descricao, p.fornecedor_id, 
                       COALESCE(e.estoque, 0) as estoque,
                       COALESCE(e.preco, 0) as preco
                FROM produtos p
                LEFT JOIN estoque e ON p.id = e.produto_id
                ORDER BY p.id ASC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produto = new Produto($row['id'], $row['nome'], $row['descricao'], $row['fornecedor_id']);
            $estoque = (int) $row['estoque'];
            $preco = (float) $row['preco'];
            $produtosEstoque[] = new ProdutoEstoque($produto, $estoque, $preco);
        }
        return $produtosEstoque;
    }

    public function removePorId($id)
    {
        $query = "DELETE FROM " . $this->table_name .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function altera($produto)
    {

        $query = "UPDATE " . $this->table_name .
            " SET nome = :nome, descricao = :descricao, fornecedor_id = :fornecedor_id" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $nome = $produto->getNome();
        $descricao = $produto->getDescricao();
        $fornecedor_id = $produto->getFornecedorId();
        $id = $produto->getId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":fornecedor_id", $fornecedor_id);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function buscaPorId($id)
    {
        $produto = null;

        $query = "SELECT
                    id, nome, descricao, fornecedor_id
                FROM
                    " . $this->table_name . "
                WHERE
                    id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, (int) $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $produto = new Produto($row['id'], $row['nome'], $row['descricao'], $row['fornecedor_id']);
        }

        return $produto;
    }

    public function buscaComNomePaginado($nome, $inicio, $quantos)
    {
        $produtos = array();

        $query = "SELECT
                    id, nome, descricao, fornecedor_id
                FROM
                    " . $this->table_name .
            " WHERE UPPER(nome) LIKE ?" .
            " ORDER BY id ASC" .
            " LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, '%' . strtoupper($nome) . '%');
        $stmt->bindValue(2, $quantos);
        $stmt->bindValue(3, $inicio);
        $stmt->execute();

        $filter_query = $query . "LIMIT " . $quantos . " OFFSET " . $inicio . '';
        error_log("---> DAO Query : " . $filter_query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $produtos[] = new Produto($id, $nome, $descricao, $fornecedor_id);
        }

        return $produtos;
    }

    public function contaComNome($nome)
    {

        $quantos = 0;

        $query = "SELECT COUNT(*) AS contagem FROM " .
            $this->table_name .
            " WHERE UPPER(nome) LIKE ? ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, '%' . strtoupper($nome) . '%');

        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quantos = $contagem;
        }

        return $quantos;
    }
}
?>