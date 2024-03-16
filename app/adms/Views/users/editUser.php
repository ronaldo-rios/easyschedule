<h1>Editar Usuário</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-edituser">
    <input type="hidden" name="id" value="<?=$this->data['editUser']['id'];?>">

    <label for="image">Imagem</label><br>
    <input type="file" id="image" name="image" value="<?=$this->data['editUser']['image'];?>"><br><br>
    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Digite o nome completo" value="<?=$this->data['editUser']['name'];?>" required><br><br>
    <label for="nickname">Apelido</label><br>
    <input type="text" id="nickname" name="nickname" oninput="toUpperCase(event)" placeholder="Digite o apelido" value="<?=$this->data['editUser']['nickname'];?>"><br><br>
    <label for="email">E-mail</label><br>
    <input type="email" id="email" name="email" placeholder="Digite o e-mail" value="<?=$this->data['editUser']['email'];?>" required><br><br>
    <label for="user">Usuário</label><br>
    <input type="text" id="user" name="user" oninput="toUpperCase(event)" placeholder="Digite o usuário" value="<?=$this->data['editUser']['user'];?>" required><br><br>

    <button type="submit" name="sendEditUser" value="Atualizar">Atualizar</button>
</form>

<script src="<?= URL . 'app/adms/assets/js/toUpper.js'?>"></script>