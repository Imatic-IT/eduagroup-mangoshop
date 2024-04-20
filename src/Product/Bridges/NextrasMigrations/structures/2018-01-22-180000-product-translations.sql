CREATE TABLE `product_translations` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`product_id` int(10) unsigned NOT NULL,
	`locale_id` int(10) unsigned NOT NULL,
	`name` varchar(1000) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `locale_id` (`locale_id`),
	KEY `product_id_locale_id` (`product_id`,`locale_id`),
	CONSTRAINT `product_translations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
	CONSTRAINT `product_translations_ibfk_2` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `product_variant_translations` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`product_variant_id` int(10) unsigned NOT NULL,
	`locale_id` int(10) unsigned NOT NULL,
	`name` varchar(1000) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `product_variant_id_locale_id` (`product_variant_id`,`locale_id`),
	KEY `locale_id` (`locale_id`),
	CONSTRAINT `product_variant_translations_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`),
	CONSTRAINT `product_variant_translations_ibfk_2` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
