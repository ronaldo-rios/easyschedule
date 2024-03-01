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
VALUES ('User to Test', 'usertotest', 'suporte@teste.com', 'usertest' , '$2y$10$/.TYF6aI9NHA.b1w9s40xuGZzoGHW/31XMWAPyPXHqHgyc3NZAHde', NOW());
