INSERT INTO products (code, enabled, created_at)
	SELECT
		CONCAT('product-', wp_icl_translations.element_id) AS code,
		1 AS enabled,
		wp_posts.post_date
	FROM wp_posts
		JOIN wp_icl_translations ON (
			wp_icl_translations.element_type = 'post_product'
			AND wp_icl_translations.element_id = wp_posts.ID
			AND wp_icl_translations.source_language_code IS NULL
			);

INSERT INTO product_variants (code, product_id, enabled, created_at)
	SELECT
		CONCAT('variant-', wp_icl_translations.element_id) AS code,
		products.id,
		1 AS enabled,
		wp_posts.post_date
	FROM wp_posts
		JOIN wp_icl_translations ON (
			wp_icl_translations.element_type = 'post_variant'
			AND wp_icl_translations.element_id = wp_posts.ID
			AND wp_icl_translations.source_language_code IS NULL
			)
		JOIN wp_postmeta ON (
			post_id = wp_icl_translations.element_id
			AND meta_key = 'product'
			)
		JOIN products ON products.code = CONCAT('product-', meta_value);
