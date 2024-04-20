CREATE TABLE `checkout_option_group` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`currency_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `currency_id` (`currency_id`),
	CONSTRAINT `checkout_option_group_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `checkout_options` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`checkout_option_group_id` int(10) unsigned NOT NULL,
	`shipping_method_id` int(10) unsigned NOT NULL,
	`payment_method_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `channel_id_shipping_method_id_payment_method_id` (`checkout_option_group_id`,`shipping_method_id`,`payment_method_id`),
	KEY `shipping_method_id` (`shipping_method_id`),
	KEY `payment_method_id` (`payment_method_id`),
	CONSTRAINT `checkout_options_ibfk_2` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`),
	CONSTRAINT `checkout_options_ibfk_3` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
	CONSTRAINT `checkout_options_ibfk_4` FOREIGN KEY (`checkout_option_group_id`) REFERENCES `checkout_option_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `channels` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`name` varchar(100) NOT NULL,
	`default_locale_id` int(10) unsigned NOT NULL,
	`pricing_group_id` int(10) unsigned NOT NULL,
	`checkout_option_group_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`),
	KEY `default_locale_id` (`default_locale_id`),
	KEY `pricing_group_id` (`pricing_group_id`),
	KEY `checkout_option_group_id` (`checkout_option_group_id`),
	CONSTRAINT `channels_ibfk_1` FOREIGN KEY (`default_locale_id`) REFERENCES `locales` (`id`),
	CONSTRAINT `channels_ibfk_3` FOREIGN KEY (`pricing_group_id`) REFERENCES `product_pricing_groups` (`id`),
	CONSTRAINT `channels_ibfk_4` FOREIGN KEY (`checkout_option_group_id`) REFERENCES `checkout_option_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `channel_locales` (
	`channel_id` int(10) unsigned NOT NULL,
	`locale_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`channel_id`,`locale_id`),
	KEY `locale_id` (`locale_id`),
	CONSTRAINT `channel_locales_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`),
	CONSTRAINT `channel_locales_ibfk_2` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
