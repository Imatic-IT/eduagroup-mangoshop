<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Product\Model\ProductVariantPricing;


class ProductVariantPricingStruct
{
	/** @var int */
	public $priceCent;

	/** @var int|null */
	public $originalPriceCents;


	public function __construct(int $priceCent, ?int $originalPriceCents)
	{
		$this->priceCent = $priceCent;
		$this->originalPriceCents = $originalPriceCents;
	}


	public static function createFromProductVariantPricing(ProductVariantPricing $productVariantPricing): self
	{
		return new self(
			$productVariantPricing->priceCents,
			$productVariantPricing->originalPriceCents
		);
	}
}
