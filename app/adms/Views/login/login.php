<h1>Área Restrita</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-login">
    <label for="user">Usuário</label><br>
    <input type="text" id="user" name="user" oninput="toUpperCase(event)" placeholder="Digite o usuário"><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha"><br><br>

    <button type="submit" name="sendLogin" value="Acessar">Acessar</button>
</form>

<p><a href="<?= URL . "new-user/index"; ?>">Cadastrar</a></p>

<!-- Script para converter o texto do input para maiúsculo: -->
<script>
    function toUpperCase(event) {
    var userInput = event.target;
    userInput.value = userInput.value.toUpperCase();
}
</script>