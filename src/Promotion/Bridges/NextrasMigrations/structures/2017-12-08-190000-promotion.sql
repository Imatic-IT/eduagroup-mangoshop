CREATE TABLE `promotions` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`percent_discount` float NOT NULL,
	`starts_at` datetime DEFAULT NULL,
	`ends_at` datetime DEFAULT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `promotion_coupons` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`promotion_id` int(10) unsigned NOT NULL,
	`code` varchar(100) CHARACTER SET ascii NOT NULL,
	`usage_limit` int(10) unsigned DEFAULT NULL,
	`used_count` int(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`),
	KEY `promotion_id` (`promotion_id`),
	CONSTRAINT `promotion_coupons_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
