<?php
include_once __DIR__ . '/../includes/db.connection.php'; // ADICIONE ESTA LINHA
include_once __DIR__ . '/../dao/PostgresProdutoDao.php';
include_once __DIR__ . '/../dao/PostgresEstoqueDao.php';
include_once __DIR__ . '/../dao/PostgresFornecedorDao.php';
include_once __DIR__ . '/../models/Produto.php';
include_once __DIR__ . '/../models/Estoque.php';
include_once __DIR__ . '/../models/Fornecedor.php';

$conn = getConnection();

$daoProduto = new PostgresProdutoDao($conn);
$daoEstoque = new PostgresEstoqueDao($conn);
$daoFornecedor = new PostgresFornecedorDao($conn);

$produtos = $daoProduto->buscaProdutosComEstoquePaginado();

$total_data = count($produtos);

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

if($total_data > 0)
{
    foreach($produtos as $produto)
    {
        $fornecedor = $daoFornecedor->buscaPorId($produto->getFornecedorId());
        $estoqueObj = $daoEstoque->buscaPorId($produto->getId());

        $preco = $estoqueObj ? number_format($estoqueObj->getPreco(), 2, ',', '.') : 'R$ 0,00';
        $estoque = $estoqueObj ? $estoqueObj->getEstoque() : 0;

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
            <td>'.$botao.'</td>
        </tr>
        ';
    }
}
else
{
    $output .= '
    <tr>
        <td colspan="7" align="center">Nenhum produto encontrado</td>
    </tr>
    ';
}

$output .= '</table>';

echo $output;
?>