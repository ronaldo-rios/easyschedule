<h1>Novo Nível de Acesso</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-addaccess">
    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Nome do nível de acesso" required><br><br>

    <button type="submit" name="sendAddAccessLevel" value="Cadastrar">Cadastrar</button>
</form>