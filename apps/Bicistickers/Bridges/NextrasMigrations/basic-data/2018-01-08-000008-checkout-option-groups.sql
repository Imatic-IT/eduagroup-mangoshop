INSERT INTO `checkout_option_group` (`id`, `name`, `currency_id`) VALUES
	(1,	'USD',	1),
	(2,	'EUR',	2),
	(3,	'CZK',	3);


INSERT INTO `checkout_options` (`id`, `checkout_option_group_id`, `shipping_method_id`, `payment_method_id`) VALUES
	(1,	1,	1,	1),
	(2,	1,	1,	2),
	(3,	2,	1,	1),
	(4,	2,	1,	2),
	(5,	3,	1,	1),
	(6,	3,	1,	2);
