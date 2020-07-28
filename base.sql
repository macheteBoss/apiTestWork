CREATE TABLE `categories` (
	`id` INT NOT NULL,
	`title` varchar(240) NOT NULL,
	`parent_id` INT DEFAULT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `products` (
	`id` INT NOT NULL,
	`title` varchar(240) NOT NULL,
	`short_description` varchar(240) NOT NULL,
	`image_url` varchar(240) NOT NULL,
	`amount` INT NOT NULL,
	`price` FLOAT(3) NOT NULL,
	`producer` varchar(240) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `cat_prod` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`cat_id` INT NOT NULL,
	`prod_id` INT NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `categories` ADD CONSTRAINT `categories_fk0` FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`);

ALTER TABLE `cat_prod` ADD CONSTRAINT `cat_prod_fk0` FOREIGN KEY (`cat_id`) REFERENCES `categories`(`id`);

ALTER TABLE `cat_prod` ADD CONSTRAINT `cat_prod_fk1` FOREIGN KEY (`prod_id`) REFERENCES `products`(`id`);
