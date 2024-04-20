CREATE TABLE `payment_methods` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`enabled` tinyint(1) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `payment_states` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`payment_method_id` int(10) unsigned NOT NULL,
	`previous_version_id` int(10) unsigned DEFAULT NULL,
	`created_at` datetime NOT NULL,
	`internal_state_code` enum('created','approved','failed') CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`internal_state_failure_reason` enum('timeouted','canceled','denied','reversed','refunded','driver','unknown') CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
	`external_state_code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
	`external_state_data` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `payment_method_id` (`payment_method_id`),
	KEY `previous_version_id` (`previous_version_id`),
	CONSTRAINT `payment_states_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
	CONSTRAINT `payment_states_ibfk_2` FOREIGN KEY (`previous_version_id`) REFERENCES `payment_states` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `payments` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`payment_method_id` int(10) unsigned NOT NULL,
	`amount_cents` bigint(20) NOT NULL,
	`amount_currency_id` int(10) unsigned NOT NULL,
	`locale_id` int(10) unsigned NOT NULL,
	`state_id` int(10) unsigned NOT NULL,
	`external_identifier` varchar(100) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
	`created_at` datetime NOT NULL,
	`approved_at` datetime DEFAULT NULL,
	`failed_at` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `payment_method_id_external_identifier` (`payment_method_id`,`external_identifier`),
	KEY `amount_currency_id` (`amount_currency_id`),
	KEY `locale_id` (`locale_id`),
	KEY `state_id` (`state_id`),
	CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
	CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`amount_currency_id`) REFERENCES `currencies` (`id`),
	CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`),
	CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`state_id`) REFERENCES `payment_states` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
