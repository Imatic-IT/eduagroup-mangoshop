INSERT INTO `channels` (`id`, `code`, `name`, `default_locale_id`, `pricing_group_id`, `checkout_option_group_id`) VALUES
	(1,	'en',	'English',	1,	1,	1),
	(2,	'de',	'German',	2,	2,	2),
	(3,	'fr',	'French',	3,	2,	2),
	(4,	'it',	'Italian',	4,	2,	2),
	(5,	'es',	'Spanish',	5,	2,	2),
	(6,	'pt',	'Portuguese',	6,	2,	2),
	(7,	'sv',	'Swedish',	7,	2,	2),
	(8,	'no',	'Norwegian',	8,	2,	2),
	(9,	'nl',	'Dutch',	9,	2,	2),
	(10,	'da',	'Danish',	10,	2,	2),
	(11,	'pl',	'Polish',	11,	2,	2),
	(12,	'cs',	'Czech',	12,	3,	3);


INSERT INTO `channel_locales` (`channel_id`, `locale_id`) VALUES
	(1,	1),
	(2,	2),
	(3,	3),
	(4,	4),
	(5,	5),
	(6,	6),
	(7,	7),
	(8,	8),
	(9,	9),
	(10,	10),
	(11,	11),
	(12,	12);
