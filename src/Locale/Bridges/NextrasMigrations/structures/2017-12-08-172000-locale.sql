CREATE TABLE `locales` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(5) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
