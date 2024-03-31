<h1>Novo Grupo de Página</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-add-pagegroup">
    <label for="group_name">Nome do Grupo</label><br>
    <input type="text" id="group_name" name="group_name" placeholder="Nome do grupo de página" required><br><br>

    <button type="submit" name="sendAddPageGroup" value="Cadastrar">Cadastrar</button>
</form>