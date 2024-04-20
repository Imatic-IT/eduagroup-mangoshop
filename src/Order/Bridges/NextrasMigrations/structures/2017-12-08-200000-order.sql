CREATE TABLE `customers` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`email` varchar(250) CHARACTER SET ascii NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `sessions` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`token` char(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`cart_id` int(10) unsigned DEFAULT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `token` (`token`),
	UNIQUE KEY `cart_id` (`cart_id`),
	CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `order_billing_info` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`address_id` int(10) unsigned NOT NULL,
	`vat_identifier` varchar(20) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
	`company_identifier` varchar(20) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `address` (`address_id`),
	CONSTRAINT `order_billing_info_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `order_contexts` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`channel_id` int(10) unsigned NOT NULL,
	`currency_id` int(10) unsigned NOT NULL,
	`locale_id` int(10) unsigned NOT NULL,
	`session_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `channel_id` (`channel_id`),
	KEY `currency_id` (`currency_id`),
	KEY `locale_id` (`locale_id`),
	KEY `session_id` (`session_id`),
	CONSTRAINT `order_contexts_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`),
	CONSTRAINT `order_contexts_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
	CONSTRAINT `order_contexts_ibfk_3` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`),
	CONSTRAINT `order_contexts_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `order_product_items` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`order_id` int(10) unsigned NOT NULL,
	`product_variant_id` int(10) unsigned NOT NULL,
	`product_variant_configuration` text COMMENT 'JSON',
	`quantity` int(10) unsigned NOT NULL,
	`unit_price_cents` bigint(20) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `order_id` (`order_id`),
	KEY `product_variant_id` (`product_variant_id`),
	CONSTRAINT `order_product_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
	CONSTRAINT `order_product_items_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `order_promotions` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`order_id` int(10) unsigned NOT NULL,
	`promotion_id` int(10) unsigned NOT NULL,
	`promotion_coupon_id` int(10) unsigned DEFAULT NULL,
	`price_cents` bigint(20) NOT NULL COMMENT 'should be negative',
	PRIMARY KEY (`id`),
	KEY `promotion_id` (`promotion_id`),
	KEY `promotion_coupon_id` (`promotion_coupon_id`),
	KEY `order_id` (`order_id`),
	CONSTRAINT `order_promotions_ibfk_3` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`),
	CONSTRAINT `order_promotions_ibfk_4` FOREIGN KEY (`promotion_coupon_id`) REFERENCES `promotion_coupons` (`id`),
	CONSTRAINT `order_promotions_ibfk_5` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `order_shipping_info` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`address_id` int(10) unsigned NOT NULL,
	`phone` varchar(100) DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `address_id` (`address_id`),
	CONSTRAINT `order_shipping_info_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `order_processing` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME NOT NULL,
	`previous_version_id` int(10) unsigned DEFAULT NULL,
	`state` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
	`data` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `previous_version_id` (`previous_version_id`),
	CONSTRAINT `order_processing_ibfk_1` FOREIGN KEY (`previous_version_id`) REFERENCES `order_processing` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `carts` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`previous_version_id` int(10) unsigned DEFAULT NULL,
	`context_id` int(10) unsigned NOT NULL,
	`customer_id` int(10) unsigned DEFAULT NULL,
	`shipping_info_id` int(10) unsigned DEFAULT NULL,
	`shipping_method_id` int(10) unsigned DEFAULT NULL,
	`billing_info_id` int(10) unsigned DEFAULT NULL,
	`payment_method_id` int(10) unsigned DEFAULT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `previous_version_id` (`previous_version_id`),
	KEY `customer_id` (`customer_id`),
	KEY `shipping_method_id` (`shipping_method_id`),
	KEY `payment_method_id` (`payment_method_id`),
	KEY `context_id` (`context_id`),
	KEY `shipping_info_id` (`shipping_info_id`),
	KEY `billing_info_id` (`billing_info_id`),
	CONSTRAINT `carts_ibfk_10` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
	CONSTRAINT `carts_ibfk_4` FOREIGN KEY (`shipping_info_id`) REFERENCES `order_shipping_info` (`id`),
	CONSTRAINT `carts_ibfk_5` FOREIGN KEY (`billing_info_id`) REFERENCES `order_billing_info` (`id`),
	CONSTRAINT `carts_ibfk_6` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
	CONSTRAINT `carts_ibfk_7` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`),
	CONSTRAINT `carts_ibfk_8` FOREIGN KEY (`context_id`) REFERENCES `order_contexts` (`id`),
	CONSTRAINT `carts_ibfk_9` FOREIGN KEY (`previous_version_id`) REFERENCES `carts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `cart_product_items` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`cart_id` int(10) unsigned NOT NULL,
	`product_variant_id` int(10) unsigned NOT NULL,
	`product_variant_configuration` text COMMENT 'JSON',
	`quantity` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `cart_id` (`cart_id`),
	KEY `product_variant_id` (`product_variant_id`),
	CONSTRAINT `cart_product_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
	CONSTRAINT `cart_product_items_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `cart_promotions` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`cart_id` int(10) unsigned NOT NULL,
	`promotion_id` int(10) unsigned NOT NULL,
	`promotion_coupon_id` int(10) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `cart_id` (`cart_id`),
	KEY `promotion_coupon_id` (`promotion_coupon_id`),
	KEY `promotion_id` (`promotion_id`),
	CONSTRAINT `cart_promotions_ibfk_3` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`),
	CONSTRAINT `cart_promotions_ibfk_4` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
	CONSTRAINT `cart_promotions_ibfk_5` FOREIGN KEY (`promotion_coupon_id`) REFERENCES `promotion_coupons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `orders` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`cart_id` int(10) unsigned NOT NULL,
	`context_id` int(10) unsigned NOT NULL,
	`customer_id` int(10) unsigned NOT NULL,
	`shipping_info_id` int(10) unsigned NOT NULL,
	`shipping_method_id` int(10) unsigned NOT NULL,
	`billing_info_id` int(10) unsigned NOT NULL,
	`payment_id` int(10) unsigned NOT NULL,
	`processing_id` int(10) unsigned DEFAULT NULL,
	`state` enum('waiting_for_payment','processing','dispatched','fulfilled', 'failed') NOT NULL,
	`failure_reason` enum('payment_failed', 'cancel_shop', 'cancel_customer', 'cancel_system', 'return_carrier', 'return_customer'),
	`created_at` datetime NOT NULL,
	`processing_started_at` datetime DEFAULT NULL,
	`dispatched_at` datetime DEFAULT NULL,
	`fulfilled_at` datetime DEFAULT NULL,
	`failed_at` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `locale_id` (`context_id`),
	UNIQUE KEY `shipping_info_id` (`shipping_info_id`),
	UNIQUE KEY `billing_info_id` (`billing_info_id`),
	UNIQUE KEY `last_payment_id` (`payment_id`),
	KEY `customer_id` (`customer_id`),
	KEY `shipping_method_id` (`shipping_method_id`),
	KEY `cart_id` (`cart_id`),
	CONSTRAINT `orders_ibfk_10` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
	CONSTRAINT `orders_ibfk_11` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`),
	CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`shipping_info_id`) REFERENCES `order_shipping_info` (`id`),
	CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`billing_info_id`) REFERENCES `order_billing_info` (`id`),
	CONSTRAINT `orders_ibfk_6` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
	CONSTRAINT `orders_ibfk_7` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`),
	CONSTRAINT `orders_ibfk_9` FOREIGN KEY (`context_id`) REFERENCES `order_contexts` (`id`),
	CONSTRAINT `orders_ibfk_12` FOREIGN KEY (`processing_id`) REFERENCES `order_processing` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



