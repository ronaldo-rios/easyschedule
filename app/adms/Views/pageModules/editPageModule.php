<h1>Atualizar Módulo de Página</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-edit-page-module">
    <input type="hidden" name="id" value="<?= $this->data['editModule']['id'] ?>">

    <label for="type">Tipo</label><br>
    <input type="text" id="type" name="type" placeholder="Tipo de pacote / módulo" value="<?= $this->data['editModule']['type'] ?>" required><br><br>
    <label for="name">Nome do Módulo</label><br>
    <input type="text" id="name" name="name" placeholder="Nome do módulo de página" value="<?= $this->data['editModule']['name'] ?>" required><br><br>
    <label for="obs">Observações</label><br>
    <input type="text" id="obs" name="obs" placeholder="Observações" value="<?= $this->data['editModule']['obs'] ?>"><br><br>

    <button type="submit" name="sendEditPageModule" value="Atualizar">Atualizar</button>
</form>