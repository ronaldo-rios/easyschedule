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
    <input type="text" id="user" name="user" oninput="toUpperCase(event)" placeholder="Digite o usuário" required><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha" required><br><br>

    <button type="submit" name="sendLogin" value="Acessar">Acessar</button>
</form>

<p><a href="<?= URL . 'new-user/index'; ?>">Cadastrar</a></p>
<p><a href="<?= URL . 'recover-password/index'; ?>">Esqueci minha senha</a></p>

<script src="<?= URL . 'app/adms/assets/js/toUpper.js'?>"></script>