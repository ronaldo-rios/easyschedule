<h1>Adição de Nova Página</h1>

<?php
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<span id="msg"></span>

<form action="" method="POST" id="form-add-page">
    <label for="name">Nome da Página</label><br>
    <input type="text" id="name" name="name" placeholder="Nome da página" required><br><br>
    <label for="controller">Controller/ Classe</label><br>
    <input type="text" id="controller" name="controller" placeholder="Nome do controller / classe" required><br><br>
    <label for="method">Método</label><br>
    <input type="text" id="method" name="method" placeholder="Nome do método"><br><br>
    <label for="controllermenu">Controller/ Classe no Menu</label><br>
    <input type="text" id="controllermenu" name="controllermenu" placeholder="Nome da controller no menu" required><br><br>
    <label for="methodmenu">Método no Menu</label><br>
    <input type="text" id="methodmenu" name="methodmenu" placeholder="Nome do método no menu" required><br><br>
    <label for="icon">Ícone</label><br>
    <input type="text" id="icon" name="icon" placeholder="Ícone"><br><br>
    <label for="obs">Observação</label><br>
    <input type="text" id="obs" name="obs" placeholder="Observação"></input><br><br>
    <label for="public">Página Pública</label><br>
    <select id="public" name="public" required>
        <option value="1">Sim</option>
        <option value="0">Não</option>
    </select><br><br>
    <label for="status">Status da Página</label><br>
    <?php foreach($this->data['select_status'] as $status): ?>
        <input type="radio" id="status" name="status" value="<?= $status['id']; ?>" <?= $status['id'] == 1 ? 'checked' : ''; ?> required>
        <label for="status"><?= $status['status']; ?></label>
    <?php endforeach; ?><br><br>
    
    <label for="group">Grupo da Página</label><br>
    <select id="group" name="group" required>
        <option value="">Selecione</option>
        <?php
            foreach($this->data['select_group'] as $group): ?>
                <?php if($this->data['select_group'] == $group['id']): ?>
                    <option value='<?= $group['id']; ?>' selected><?= $group['group_name']; ?></option>
                <?php else: ?>
                    <option value='<?= $group['id']; ?>'><?= $group['group_name'];?></option>
                <?php endif; ?>
            <? endforeach;
        ?>
    </select><br><br>

    <label for="module">Módulo da Página</label><br>
    <select id="module" name="module" required>
        <option value="">Selecione</option>
        <?php
            foreach($this->data['select_module'] as $module): ?>
                <?php if($this->data['select_module'] == $module['id']): ?>
                    <option value='<?= $module['id']; ?>' selected><?= $module['module_name']; ?></option>
                <?php else: ?>
                    <option value='<?= $module['id']; ?>'><?= $module['module_name'];?></option>
                <?php endif; ?>
            <? endforeach;
        ?>
    </select><br><br>

    <button type="submit" name="sendAddPage" value="Cadastrar">Cadastrar</button>
</form>