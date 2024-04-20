CREATE TABLE `countries` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`code` char(2) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'ISO 3166-1 alpha-2',
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `country_states` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`country_id` int(10) unsigned NOT NULL,
	`code` varchar(100) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
	`name` varchar(100) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `country_id` (`country_id`),
	CONSTRAINT `country_states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `addresses` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`recipient_name` varchar(1000) NOT NULL COMMENT 'first name + last name / company name',
	`line1` varchar(1000) NOT NULL,
	`line2` varchar(1000) NOT NULL,
	`city` varchar(1000) NOT NULL,
	`postal_code` varchar(20) NOT NULL,
	`state_id` int(10) unsigned DEFAULT NULL,
	`country_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `country_id` (`country_id`),
	KEY `state_id` (`state_id`),
	CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
	CONSTRAINT `addresses_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `country_states` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
