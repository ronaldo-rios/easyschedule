<h1>Editar Usuário</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-edituser" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?=$this->data['editUser']['id'];?>">

    <?php
    if (! empty($this->data['editUser']['image'])) {
        $old_image = URL . PATH_USER_IMAGE . $this->data['editUser']['id'] . "/" . $this->data['editUser']['image'];
    } else {
        $old_image = URL . PATH_USER_IMAGE . "default.png";
    }
    ?>
    <span id="preview-img">
        <img src="<?php echo $old_image; ?>" alt="Imagem" style="width: 100px; height: 100px;">
    </span><br><br>

    <label for="image">Imagem</label><br>
    <input type="file" id="image" name="image" onchange="inputFileValImg()" accept="image/png, image/jpeg, image/jpg" value="<?=$this->data['editUser']['image'];?>"><br><br>
    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Digite o nome completo" value="<?=$this->data['editUser']['name'];?>" required><br><br>
    <label for="nickname">Apelido</label><br>
    <input type="text" id="nickname" name="nickname" oninput="toUpperCase(event)" placeholder="Digite o apelido" value="<?=$this->data['editUser']['nickname'];?>"><br><br>
    <label for="email">E-mail</label><br>
    <input type="email" id="email" name="email" placeholder="Digite o e-mail" value="<?=$this->data['editUser']['email'];?>" required><br><br>
    <label for="user">Usuário</label><br>
    <input type="text" id="user" name="user" oninput="toUpperCase(event)" placeholder="Digite o usuário" value="<?=$this->data['editUser']['user'];?>" required><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha" value="<?=$this->data['editUser']['password'];?>" required><br><br>
    <label for="user_situation_id">Situação</label><br>
    <select id="user_situation_id" name="user_situation_id" required>
        <option value="">Selecione</option>
        <?php
            foreach($this->data['select'] as $situation): ?>
                <?php if($this->data['editUser']['user_situation_id'] == $situation['id']): ?>
                    <option value='<?= $situation['id']; ?>' selected><?= $situation['situation_name']; ?></option>
                <?php else: ?>
                    <option value='<?= $situation['id']; ?>'><?= $situation['situation_name'];?></option>
                <?php endif; ?>
            <? endforeach;
        ?>
    </select><br><br>

    <button type="submit" name="sendEditUser" value="Atualizar">Atualizar</button>
</form>

<script src="<?= URL . 'app/adms/assets/js/toUpper.js'?>"></script>