<h1>Novo Usuário</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-adduser">
    <label for="image">Imagem</label><br>
    <input type="file" id="image" name="image" ><br><br>
    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Digite o nome completo" required><br><br>
    <label for="nickname">Apelido</label><br>
    <input type="text" id="nickname" name="nickname" oninput="toUpperCase(event)" placeholder="Digite o apelido" required><br><br>
    <label for="email">E-mail</label><br>
    <input type="email" id="email" name="email" placeholder="Digite o e-mail" required><br><br>
    <label for="user">Usuário</label><br>
    <input type="text" id="user" name="user" oninput="toUpperCase(event)" placeholder="Digite o usuário" required><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha" required><br><br>

    <button type="submit" name="sendAddUser" value="Cadastrar">Cadastrar</button>
</form>

<p><a href="<?= URL . "login/index"; ?>">Clique aqui</a> para acessar</p>

<script src="<?= URL . 'app/adms/assets/js/toUpper.js'?>"></script>