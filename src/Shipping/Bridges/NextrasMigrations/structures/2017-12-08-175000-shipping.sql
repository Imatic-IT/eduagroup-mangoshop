CREATE TABLE `shipping_methods` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`enabled` tinyint(1) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
