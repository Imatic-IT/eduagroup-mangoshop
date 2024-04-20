<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Product\Model\ProductVariant;


class ProductVariantStruct
{
	/** @var bool */
	public $enabled;


	public function __construct(bool $enabled)
	{
		$this->enabled = $enabled;
	}


	public static function createFromProductVariant(ProductVariant $productVariant): self
	{
		return new self(
			$productVariant->enabled
		);
	}
}
