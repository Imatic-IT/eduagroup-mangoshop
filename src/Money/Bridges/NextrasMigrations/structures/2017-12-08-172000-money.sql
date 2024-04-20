CREATE TABLE `currencies` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` char(3) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
