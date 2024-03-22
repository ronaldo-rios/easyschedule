<h1>Atualizar Servidor de Email</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-editemailservers">
    <input type="hidden" name="id" value="<?= $this->data['editEmailServer']['id'] ?>">

    <label for="name">Nome</label><br>
    <input type="text" id="name" name="name" placeholder="Digite o nome do servidor de e-mail" value="<?=$this->data['editEmailServer']['name'];?>" required><br><br>
    <label for="port">Porta</label><br>
    <input type="number" id="port" name="port" placeholder="Digite a porta" value="<?=$this->data['editEmailServer']['port'];?>"><br><br>
    <label for="email">E-mail</label><br>
    <input type="email" id="email" name="email" placeholder="Digite o e-mail" value="<?=$this->data['editEmailServer']['email'];?>" required><br><br>
    <label for="username">Usuário</label><br>
    <input type="text" id="user" name="username" placeholder="Digite o usuário" value="<?=$this->data['editEmailServer']['username'];?>" required><br><br>
    <label for="smtp_secure">Smtp</label><br>
    <input type="text" id="smtp_secure" name="smtp_secure" placeholder="Digite o smtp" value="<?=$this->data['editEmailServer']['smtp_secure'];?>" required><br><br>
    <label for="host">Host</label><br>
    <input type="text" id="host" name="host" placeholder="Digite o host" value="<?=$this->data['editEmailServer']['host'];?>" required><br><br>
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite a senha" value="<?=$this->data['editEmailServer']['password'];?>"required><br><br>

    <button type="submit" name="sendEditEmailServers" value="Atualizar">Atualizar</button>
</form>
