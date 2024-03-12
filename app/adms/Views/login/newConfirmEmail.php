<h1>Novo Link</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-new-confirm-email">
    <label for="emailconfirm">E-mail</label><br>
    <input type="email" id="emailconfirm" name="email" placeholder="Digite o seu e-mail" required><br><br>

    <button type="submit" name="sendNewConfirmEmail" value="Enviar">Enviar</button>
</form>

<p><a href="<?= URL . "login/index"; ?>">Clique aqui</a> para acessar</p>

<script>
const formNewConfirmEmail = document.getElementById('form-new-confirm-email')

if (formNewConfirmEmail) {
    formNewConfirmEmail.addEventListener('submit', async(e) => {

    // Check if the field email is empty
    let confirmEmail = document.querySelector('#emailconfirm').value
        if (confirmEmail === '') {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo e-mail é obrigatório.</p>'
            return
        }

    })
}
</script>