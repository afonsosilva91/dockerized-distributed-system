DROP DATABASE IF EXISTS `database`;
CREATE DATABASE IF NOT EXISTS `database`;

USE `database`;


CREATE TABLE `users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(50),
    `password` VARCHAR(50),
    `email` VARCHAR(50),
	`active` BOOLEAN,
    PRIMARY KEY (`id`)
);

INSERT INTO `users` (`username`, `password`, `email`, `active`) 
VALUES ("helloprint", "P@ssw0rd!", "xxxxxx@xxxxxx.xxx", 1);
