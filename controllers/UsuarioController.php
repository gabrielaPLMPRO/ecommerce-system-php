<?php
require_once 'models/Usuario.php';

class UsuarioController {
    
    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $tipo = $_POST['tipo'];

            $usuario = new Usuario();
            if ($usuario->cadastrar($nome, $email, $senha, $tipo)) {
                echo "Cadastro realizado com sucesso!";
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $usuario = new Usuario();
            $user = $usuario->login($email, $senha);

            if ($user) {
                echo "Bem-vindo, " . $user['nome'];
            } else {
                echo "Email ou senha inválidos.";
            }
        }
    }
}
?>
