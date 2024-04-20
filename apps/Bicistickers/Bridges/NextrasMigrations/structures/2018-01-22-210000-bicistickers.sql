RENAME TABLE product_translations TO product_translations_original;
CREATE VIEW product_translations AS
	SELECT
		translation_result.translation_id AS id,
		products.id AS product_id,
		locales.id AS locale_id,
		wp_posts.post_title AS name

	FROM wp_posts
		JOIN wp_icl_translations translation_result ON (
			translation_result.element_type = 'post_product'
			AND translation_result.element_id = wp_posts.ID
		)
		JOIN wp_icl_translations translation_source ON (
			translation_source.element_type = 'post_product'
			AND translation_source.trid = translation_result.trid
			AND translation_source.source_language_code IS NULL
		)
		JOIN products ON products.code = CONCAT('product-', translation_source.element_id)
		JOIN locales ON locales.code = translation_result.language_code;


RENAME TABLE product_variant_translations TO product_variant_translations_original;
CREATE VIEW product_variant_translations AS
	SELECT
		translation_result.translation_id AS id,
		product_variants.id AS product_variant_id,
		locales.id AS locale_id,
		wp_posts.post_title AS name

	FROM wp_posts
		JOIN wp_icl_translations translation_result ON (
			translation_result.element_type = 'post_variant'
			AND translation_result.element_id = wp_posts.ID
		)
		JOIN wp_icl_translations translation_source ON (
			translation_source.element_type = 'post_variant'
			AND translation_source.trid = translation_result.trid
			AND translation_source.source_language_code IS NULL
		)
		JOIN product_variants ON product_variants.code = CONCAT('variant-', translation_source.element_id)
		JOIN locales ON locales.code = translation_result.language_code;
