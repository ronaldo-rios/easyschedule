CREATE DATABASE easy_schedule
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE easy_schedule;

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
    FOREIGN KEY (`color_id`) REFERENCES `colors`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_users_with_user_situation_id`
  FOREIGN KEY (`user_situation_id`) REFERENCES `users_situation`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `colors`(color_name, color, created_at) VALUES('Azul', '#0275D8', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Cinza', '#868E95', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Verde', '#5CB85C', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Vermelho', '#D9534F', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Laranja', '#F0AD4E', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Azul Claro', '#17A2B8', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Cinza Claro', '#343140', NOW());
INSERT INTO `colors`(color_name, color, created_at) VALUES('Branco', '#FFFFFF', NOW());

INSERT INTO `users_situation`(situation_name, color_id, created_at) VALUES('Confirmado', 3, NOW());
INSERT INTO `users_situation`(situation_name, color_id, created_at) VALUES('Aguardando Confirmação', 5, NOW());
INSERT INTO `users_situation`(situation_name, color_id, created_at) VALUES('Não Cadastrado', 4, NOW());

INSERT INTO `users` (`name`, `nickname`, `email`, `user`, `password`, `user_situation_id`, `created_at`)
VALUES 
('User to Test', 'USERTOTEST', 'suporte@teste.com', 'USERTEST' , '$2y$10$hqgJV15KX4k8e06PY.aL7OqSnHA0at.ng5iamwGKBpcJRDdYLCSB2', 2, NOW());

-- Example of email configuration. Select the email configuration that you want to use and insert the data in the table config_emails
-- INSERT INTO `config_emails` (`title`, `name`, `email`, `host`, `username`, `password`, `smtp_secure`, `port`, `created_at`)
-- VALUES 
-- ('Confirmação', 'Confirmação de Cadastro', 'noreplay@easyschedule.com.br', 'smtp.gmail.com', '*******', '*******', 'tls', 587, NOW());

