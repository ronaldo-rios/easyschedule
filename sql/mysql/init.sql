CREATE DATABASE easy_schedule
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

SET NAMES utf8;
USE easy_schedule;

CREATE TABLE `access_levels` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `access_level` VARCHAR(100) NOT NULL,
    `order_level` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `config_emails` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(225) NOT NULL,
    `name` VARCHAR(225) NOT NULL,
    `email` VARCHAR(225) NOT NULL,
    `host` VARCHAR(225) NOT NULL,
    `username` VARCHAR(225) NOT NULL,
    `password` VARCHAR(225) NOT NULL,
    `smtp_secure` VARCHAR(225) NOT NULL,
    `port` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `colors`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `color_name` VARCHAR(100) NOT NULL,
    `color` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE `users_situation`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `situation_name` VARCHAR(100) NOT NULL,
    `color_id` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_user_situation_with_color_id`
    FOREIGN KEY (`color_id`) REFERENCES `colors`(`id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nickname` varchar(100) NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `user` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `recover_password` varchar(255) NULL,
  `image` varchar(255) NULL,
  `confirm_email` VARCHAR(225) NULL,
  `user_situation_id` INT NOT NULL,
  `access_level_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_users_with_user_situation_id`
  FOREIGN KEY (`user_situation_id`) REFERENCES `users_situation`(`id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_users_with_access_level_id`
  FOREIGN KEY (`access_level_id`) REFERENCES `access_levels`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `page_status`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `status` VARCHAR(100) NOT NULL,
    `color_id` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_page_status_with_color_id`
    FOREIGN KEY (`color_id`) REFERENCES `colors`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `page_groups` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `group_name` VARCHAR(225) NOT NULL,
    `order_page_group` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE `page_modules` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(225) NOT NULL,
    `name` VARCHAR(225) NOT NULL,
    `order_module` INT NOT NULL,
    `obs` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE `pages` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `controller` VARCHAR(225) NOT NULL,
    `method` VARCHAR(225) NOT NULL,
    `controller_in_the_main` VARCHAR(225) NOT NULL,
    `method_in_the_main` VARCHAR(225) NOT NULL,
    `name_page` VARCHAR(225) NOT NULL,
    `public` INT NOT NULL,
    `icon` VARCHAR(225) NULL,
    `obs` TEXT NULL,
    `page_status_id` INT NOT NULL,
    `page_group_id` INT NOT NULL,
    `page_module_id` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_pages_with_page_status_id`
    FOREIGN KEY (`page_status_id`) REFERENCES `page_status`(`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_pages_with_page_group_id`
    FOREIGN KEY (`page_group_id`) REFERENCES `page_groups`(`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_pages_with_page_module_id`
    FOREIGN KEY (`page_module_id`) REFERENCES `page_modules`(`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `page_levels`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `permission` INT NOT NULL,
    `order_level_page` INT NOT NULL,
    `access_level_id` INT NOT NULL,
    `page_id` INT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_page_levels_with_access_level_id`
    FOREIGN KEY (`access_level_id`) REFERENCES `access_levels`(`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_page_levels_with_page_id`
    FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `colors`(color_name, color, created_at) 
VALUES
    ('Azul', '#0275D8', NOW()),
    ('Cinza', '#868E95', NOW()),
    ('Verde', '#5CB85C', NOW()),
    ('Vermelho', '#D9534F', NOW()),
    ('Laranja', '#F0AD4E', NOW()),
    ('Azul Claro', '#17A2B8', NOW()),
    ('Cinza Claro', '#343140', NOW()),
    ('Branco', '#FFFFFF', NOW());

INSERT INTO `users_situation`(situation_name, color_id, created_at) 
VALUES('Confirmado', 3, NOW()), ('Aguardando Confirmação', 5, NOW()), ('Não Cadastrado', 4, NOW());

INSERT INTO `access_levels`(`access_level`, `order_level`, `created_at`) 
VALUES('Master', 1, NOW()),('Administrador', 2, NOW()),('Usuário Default', 3, NOW()),('Financeiro', 4, NOW());

INSERT INTO `page_status`(status, color_id, created_at) 
VALUES ('Ativo', 3, NOW()),('Inativo', 4, NOW());

INSERT INTO `page_groups`(group_name, order_page_group, created_at) 
VALUES
    ('Listar', 1, NOW()),
    ('Visualizar', 2, NOW()),
    ('Cadastrar', 3, NOW()),
    ('Editar', 4, NOW()),
    ('Excluir', 5, NOW()),
    ('Acesso', 6, NOW()),
    ('Outros', 7, NOW());

INSERT INTO `page_modules`(type, name, order_module, obs, created_at) 
VALUES
    ('adms', 'Administrativo', 1, 'Administração e configurações de Usuários', NOW()),
    ('sche', 'Agendamentos', 2, 'Administração de agendamentos', NOW()),
    ('finc', 'Financeiro', 3, 'Administração de finanças', NOW());

INSERT INTO `users` 
(`name`, `nickname`, `email`, `user`, `password`, `user_situation_id`, `access_level_id`, `created_at`)
VALUES -- Senha do usuário padrão sem o hash: Secret123
('User to Test', 'USERTOTEST', 'suporte@teste.com', 'USERTEST' , '$2y$10$XF23pikBWucg6xf.8RJJFebMm/2uWKLUXS6V2vaJXrhsJDA0a0nS2', 1, 1, NOW());

INSERT INTO `pages`
    (controller, method, controller_in_the_main, method_in_the_main, name_page, public, page_status_id, page_group_id, page_module_id, created_at)
VALUES
    ('Login', 'index', 'login', 'index', 'Login', 1, 1, 6, 1, NOW()),
    ('NewUser', 'index', 'new-user', 'index', 'Cadastro' 1, 1, 6, 1, NOW()),
    ('Logout', 'index', 'logout', 'index', 'Logout',1, 1, 6, 1, NOW()),
    ('Error', 'index', 'error', 'index', 'Página de erro', 1, 1, 6, 1, NOW()),
    ('ConfirmEmail', 'index', 'confirm-email', 'index', 'Confirmação de Email', 1, 1, 6, 1, NOW()),
    ('NewConfirmEmail', 'index', 'new-confirm-email', 'index', 'Nova Confirmação de Email', 1, 1, 6, 1, NOW()),
    ('RecoverPassword', 'index', 'recover-password', 'index', 'Esqueci Minha Senha', 1, 1, 6, 1, NOW()),
    ('UpdatePassword', 'index', 'update-password', 'index', 'Atualizar Senha', 1, 1, 6, 1, NOW()),
    ('Permissions', 'index', 'permissions', 'index','Permissões de Acesso', 0, 1, 6, 1, NOW()),
    ('Dashboard', 'index', 'dashboard', 'index', 'Dashboard', 0, 1, 7, 1, NOW()),
    ('SyncPageLevels', 'index', 'sync-page-levels', 'index', 'Sincronizar Página e Nível de Acesso', 0, 1, 7, 1, NOW()),
    ('Users', 'index', 'users', 'index', 'Usuários', 0,1, 2, 1, NOW()),
    ('ViewUser', 'index', 'view-user', 'index', 'Visualizar Usuário', 0, 1, 2, 1, NOW()),
    ('AddUser', 'index', 'add-user', 'index', 'Adicionar Usuário', 0, 1, 3, 1, NOW()),
    ('EditUser', 'index', 'edit-user', 'index', 'Editar Usuário', 0, 1, 4, 1, NOW()),
    ('DeleteUser', 'index', 'delete-user', 'index', 'Excluir Usuário', 0, 1, 5, 1, NOW()),

-- INSERT INTO `page_levels`
--     (permission, order_level_page, access_level_id, page_id, created_at)
-- VALUES
--     (1, 1, 1, 1, NOW());

-- Example of email configuration. Select the email configuration that you want to use and insert the data in the table config_emails
-- INSERT INTO `config_emails` (`title`, `name`, `email`, `host`, `username`, `password`, `smtp_secure`, `port`, `created_at`)
-- VALUES 
-- ('Confirmação', 'Confirmação de Cadastro', 'noreplay@easyschedule.com.br', 'smtp.gmail.com', '*******', '*******', 'tls', 587, NOW());

