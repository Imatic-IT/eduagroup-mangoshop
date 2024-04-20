INSERT INTO product_variant_pricings (
	product_pricing_group_id,
	product_variant_id,
	price_cents,
	created_at
)
	SELECT
		product_pricing_groups.id,
		product_variants.id,
		10000,
		product_variants.created_at
	FROM
		product_variants, product_pricing_groups;
