<h1>Adicionar Servidor de Email</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-addemailservers">

    <label for="title">Título do E-mail</label><br>
    <input type="text" id="title" name="title" placeholder="Digite o título do e-mail" required><br><br>
    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Digite o nome do servidor de e-mail" required><br><br>
    <label for="port">Porta</label><br>
    <input type="number" id="port" name="port" placeholder="Digite a porta" required><br><br>
    <label for="email">E-mail</label><br>
    <input type="email" id="email" name="email" placeholder="Digite o e-mail" required><br><br>
    <label for="username">Usuário</label><br>
    <input type="text" id="user" name="username" placeholder="Digite o usuário" required><br><br>
    <label for="smtp_secure">Smtp</label><br>
    <input type="text" id="smtp_secure" name="smtp_secure" placeholder="Digite o smtp" required><br><br>
    <label for="host">Host</label><br>
    <input type="text" id="host" name="host" placeholder="Digite o host" required><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha"required><br><br>

    <button type="submit" name="sendAddEmailServers" value="Salvar">Salvar</button>
</form>