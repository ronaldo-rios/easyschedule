<h1>Atualizar Nível de Acesso</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>

<span id="msg"></span>

<form action="" method="POST" id="form-editaccesslevel">
    <input type="hidden" name="id" value="<?= $this->data['editAccessLevel']['id'] ?>">

    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Nome do nível de acesso" value="<?=$this->data['editAccessLevel']['access_level'];?>" required><br><br>

    <button type="submit" name="sendEditAccessLevel" value="Atualizar">Atualizar</button>
</form>