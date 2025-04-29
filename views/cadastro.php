<?php include('../includes/header.php'); ?>
<?php if (isset($_GET['msg'])): ?>
    <div style="padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 15px;">
        <?php
        switch ($_GET['msg']) {
            case 'inserido':
                echo "Usuário cadastrado com sucesso!";
                break;
            case 'alterado':
                echo "Usuário alterado com sucesso!";
                break;
            case 'excluido':
                echo "Usuário excluído com sucesso!";
                break;
            default:
                echo "Erro: " . htmlspecialchars($_GET['msg']);
        }
        ?>
    </div>
<?php endif; ?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<div class="container">
    <div class="card-form">
        <h2>Login</h2>
        <form action="../controllers/LoginController.php" method="POST">
            <input type="hidden" name="acao" value="inserir">

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="email@exemplo.com" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <hr>
           
            <div class="text-center">
            <button type="submit" class="btn-aliexpress">Cadastrar</button>
            <br/>
            <hr>
            <a  href="login.php" class="btn-aliexpress">
               Ir para Login
            </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
