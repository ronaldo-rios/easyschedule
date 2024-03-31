<h1>Novo Módulo de Página</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-add-page-module">
    <label for="type">Tipo</label><br>
    <input type="text" id="type" name="type" placeholder="Tipo de pacote / módulo" required><br><br>
    <label for="name">Nome do Módulo</label><br>
    <input type="text" id="name" name="name" placeholder="Nome do módulo de página" required><br><br>
    <label for="obs">Observações</label><br>
    <input type="text" id="obs" name="obs" placeholder="Observações"><br><br>

    <button type="submit" name="sendAddPageModule" value="Cadastrar">Cadastrar</button>
</form>