CREATE DATABASE easy_schedule
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE easy_schedule;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `user` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `recover_password` varchar(255) NULL,
  `image` varchar(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `users` (`name`, `nickname`, `email`, `user`, `password`, `created_at`)
VALUES ('User to Test', 'USERTOTEST', 'suporte@teste.com', 'USERTEST' , '$2y$10$hqgJV15KX4k8e06PY.aL7OqSnHA0at.ng5iamwGKBpcJRDdYLCSB2', NOW());
