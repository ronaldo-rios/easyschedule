const formLogin = document.getElementById('form-login')

if (formLogin) {
    formLogin.addEventListener('submit', async(e) => {

        // Check if the fields are empty
        let user = document.querySelector('#user').value
        if (user === '') {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo usuário é obrigatório.</p>'
            return
        }

        let password = document.querySelector('#password').value
        if (password === '') {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo senha é obrigatório.</p>'
            return
        }
    })
}

const formNewUser = document.getElementById('form-newuser')

if (formNewUser) {
    formNewUser.addEventListener('submit', async(e) => {

        // Check if the fields are empty
        let nameUser = document.querySelector('#name').value
        if (nameUser === "") {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo nome é obrigatório.</p>'
            return
        }

        let nickUser = document.querySelector('#nickname').value
        if (nickUser === "") {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo apelido é obrigatório.</p>'
            return
        }

        let emailUser = document.querySelector('#email').value
        if (emailUser === "") {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo email é obrigatório.</p>'
            return
        }

        let user = document.querySelector('#user').value
        if (user === "") {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo usuário é obrigatório.</p>'
            return
        }

        let passwordUser = document.querySelector('#password').value
        if (passwordUser === "") {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Campo senha é obrigatório.</p>'
            return
        }

        // Check if the password has at least 6 characters
        if(passwordUser.length < 6){
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Senha deve ter no mínimo 6 caracteres.</p>'
            return
        }

        // Validate if the password has at least one uppercase letter, one lowercase letter and one number
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
        if (! regex.test(passwordUser)) {
            e.preventDefault()
            document.getElementById('msg').innerHTML = '<p style="color:red;">Senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número.</p>'
            return
        }

    })

}