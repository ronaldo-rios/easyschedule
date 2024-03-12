<h1>Recuperação de Senha</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="recover-password">
    <label for="emailrecover">E-mail</label><br>
    <input type="email" id="emailrecover" name="email" placeholder="Digite o seu e-mail" required><br><br>

    <button type="submit" name="sendRecoverPassword" value="Recuperar">Recuperar</button>
</form>

<p><a href="<?= URL . "login/index"; ?>">Clique aqui</a> para acessar</p>

<script>
    const formRecoverPassword = document.getElementById('recover-password')

    if (formRecoverPassword) {
        formRecoverPassword.addEventListener('submit', async(e) => {

            // Check if the fields are empty
            let emailToRecover = document.querySelector('#emailrecover').value
            if (emailToRecover == '') {
                e.preventDefault()
                document.getElementById('msg').innerHTML = '<p style="color:red;">Campo e-mail é obrigatório.</p>'
                return
            }

        })
}
</script>