<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Product\Model\ProductPricingGroup;


class ProductPricingGroupStruct
{
	/** @var string */
	public $name;


	public function __construct(string $name)
	{
		$this->name = $name;
	}


	public static function createFromProductPricingGroup(ProductPricingGroup $productPricingGroup): self
	{
		return new self(
			$productPricingGroup->name
		);
	}
}
