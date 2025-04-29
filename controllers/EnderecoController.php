<?php
require_once 'models/Endereco.php';

class EnderecoController {
    public function inserir($dados) {
        $endereco = new Endereco(
            null,  
            $dados['rua'],
            $dados['numero'],
            $dados['complemento'],
            $dados['bairro'],
            $dados['cep'],
            $dados['cidade'],
            $dados['estado']
        );

        $conn = include 'includes/db.connection.php';
        if ($endereco->salvar($conn)) {
            return "Endereço cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar endereço.";
        }
    }
}
?>
