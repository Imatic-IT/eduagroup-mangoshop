<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Product\Model\Product;
use Nette\Utils\Validators;


class ProductStruct
{
	/** @var bool */
	public $enabled;

	/** @var ProductVariantStruct[] productVariantCode =>  */
	public $variants;


	/**
	 * @param ProductVariantStruct[] $variants
	 */
	public function __construct(bool $enabled, array $variants)
	{
		assert(count($variants) > 0);
		assert(Validators::everyIs(array_keys($variants), 'string'));
		assert(Validators::everyIs($variants, ProductVariantStruct::class));

		$this->enabled = $enabled;
		$this->variants = $variants;
	}


	public static function createFromProduct(Product $product): self
	{
		$variants = [];
		foreach ($product->variants as $productVariant) {
			$variants[$productVariant->code] = ProductVariantStruct::createFromProductVariant($productVariant);
		}

		return new self(
			$product->enabled,
			$variants
		);
	}
}
