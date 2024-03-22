<h1>Atualizar Perfil</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-editprofiler" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $_SESSION['user_id']; ?>">

<?php
    if (! empty($this->data['editProfile']['image'])) {
        $old_image = URL . PATH_USER_IMAGE . $_SESSION['user_id'] . "/" . $this->data['editProfile']['image'];
    } else {
        $old_image = URL . PATH_USER_IMAGE . "default.png";
    }
?>
    <span id="preview-img">
        <img src="<?php echo $old_image; ?>" alt="Imagem" style="width: 100px; height: 100px;">
    </span><br><br>

    <label for="image">Imagem</label><br>
    <input type="file" id="image" name="image" onchange="inputFileValImg()" accept="image/png, image/jpeg, image/jpg" ><br><br>
    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Digite o nome completo" value="<?=$this->data['editProfile']['name'];?>" required><br><br>
    <label for="nickname">Apelido</label><br>
    <input type="text" id="nickname" name="nickname" oninput="toUpperCase(event)" placeholder="Digite o apelido" value="<?=$this->data['editProfile']['nickname'];?>"><br><br>
    <label for="email">E-mail</label><br>
    <input type="email" id="email" name="email" placeholder="Digite o e-mail" value="<?=$this->data['editProfile']['email'];?>" required><br><br>
    <label for="user">Usuário</label><br>
    <input type="text" id="user" name="user" oninput="toUpperCase(event)" placeholder="Digite o usuário" value="<?=$this->data['editProfile']['user'];?>" required><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha" value="<?=$this->data['editProfile']['password'];?>"required><br><br>

    <button type="submit" name="sendEditProfile" value="Atualizar">Atualizar</button>
</form>

<script src="<?= URL . 'app/adms/assets/js/toUpper.js'?>"></script>