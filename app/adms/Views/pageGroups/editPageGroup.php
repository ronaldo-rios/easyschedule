<h1>Atualizar Grupo de Página</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>

<span id="msg"></span>

<form action="" method="POST" id="form-edit-page-group">
    <input type="hidden" name="id" value="<?= $this->data['editPageGroup']['id'] ?>">

    <label for="group_name">Nome</label><br>
    <input type="text" id="group_name" name="group_name" placeholder="Nome do grupo de página" value="<?=$this->data['editPageGroup']['group_name'];?>" required><br><br>

    <button type="submit" name="sendEditPageGroup" value="Atualizar">Atualizar</button>
</form>