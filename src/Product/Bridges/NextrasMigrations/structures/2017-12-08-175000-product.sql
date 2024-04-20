CREATE TABLE `products` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`enabled` tinyint(1) NOT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `product_pricing_groups` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`currency_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `currency_id` (`currency_id`),
	CONSTRAINT `product_pricing_groups_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `product_variants` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`product_id` int(10) unsigned NOT NULL,
	`enabled` tinyint(1) NOT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`),
	KEY `product_id` (`product_id`),
	CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `product_variant_pricings` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`previous_version_id` int(10) unsigned DEFAULT NULL,
	`product_pricing_group_id` int(10) unsigned DEFAULT NULL,
	`product_variant_id` int(10) unsigned NOT NULL,
	`price_cents` bigint(20) unsigned NOT NULL COMMENT 'always in pricing group currency',
	`original_price_cents` bigint(20) unsigned DEFAULT NULL COMMENT 'always in pricing group currency',
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `product_pricing_group_id_product_variant_id` (`product_pricing_group_id`,`product_variant_id`),
	KEY `product_variant_id` (`product_variant_id`),
	KEY `previous_version_id` (`previous_version_id`),
	CONSTRAINT `product_variant_pricings_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`),
	CONSTRAINT `product_variant_pricings_ibfk_3` FOREIGN KEY (`previous_version_id`) REFERENCES `product_variant_pricings` (`id`),
	CONSTRAINT `product_variant_pricings_ibfk_4` FOREIGN KEY (`product_pricing_group_id`) REFERENCES `product_pricing_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
