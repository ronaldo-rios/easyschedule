<h1>Atualizar Senha</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="update-password">
    <label for="password">Senha</label><br>
    <input type="password" id="password" name="password" placeholder="Digite sua nova senha" required><br><br>

    <button type="submit" name="sendUpdatePassword" value="Atualizar">Atualizar</button>
</form>

<script>
    const formUpdatePass = document.getElementById('update-password')

if (formUpdatePass) {
    formUpdatePass.addEventListener('submit', async(e) => {

        let updatePassword = document.querySelector('#password').value
        if (updatePassword === "") {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo senha é obrigatório.</p>'
            return
        }

        // Check if the password has at least 6 characters
        if(updatePassword.length < 6){
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Senha deve ter no mínimo 6 caracteres.</p>'
            return
        }

        // Validate if the password has at least one uppercase letter, one lowercase letter and one number
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
        if (! regex.test(updatePassword)) {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número.</p>'
            return
        }
    })

}
</script>