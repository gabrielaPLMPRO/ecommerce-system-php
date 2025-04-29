<form action="EnderecoController.php" method="POST">
    <label for="rua">Rua:</label>
    <input type="text" name="rua" id="rua" required>

    <label for="numero">Número:</label>
    <input type="text" name="numero" id="numero" required>

    <label for="complemento">Complemento:</label>
    <input type="text" name="complemento" id="complemento">

    <label for="bairro">Bairro:</label>
    <input type="text" name="bairro" id="bairro" required>

    <label for="cep">CEP:</label>
    <input type="text" name="cep" id="cep" required>

    <label for="cidade">Cidade:</label>
    <input type="text" name="cidade" id="cidade" required>

    <label for="estado">Estado:</label>
    <input type="text" name="estado" id="estado" required>

    <button type="submit" name="acao" value="inserir">Cadastrar Endereço</button>
</form>
