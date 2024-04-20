<?php declare(strict_types = 1);

namespace MangoShopTests\Product\Inc;

use MangoShop\Money\Model\Currency;
use MangoShop\Product\Model\Product;
use MangoShop\Product\Model\ProductPricingGroup;
use MangoShop\Product\Model\ProductVariant;
use MangoShop\Product\Model\ProductVariantPricing;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;


class ProductEntityFactory extends EntityFactory
{
	public function createProduct(array $data, EntityGenerator $generator): Product
	{
		$this->verifyData(['code', 'enabled', 'variants'], $data);

		$productCode = $data['code'] ?? $this->counter(Product::class, 'P');

		$product = new Product($productCode);
		$product->setEnabled($data['enabled'] ?? true);

		$generator->createList(ProductVariant::class, $data['variants'] ?? 1, ['product' => $product]);

		return $product;
	}


	public function createProductVariant(array $data, EntityGenerator $generator): ProductVariant
	{
		$this->verifyData(['product', 'code', 'enabled', 'pricing'], $data);

		$productVariantCode = $data['code'] ?? $this->counter(ProductVariant::class, 'V');
		$product = $data['product'] ?? $generator->create(Product::class, [
			'enabled' => $data['enabled'] ?? true,
			'variants' => 0,
		]);

		$productVariant = new ProductVariant($productVariantCode, $product);
		$productVariant->setEnabled($data['enabled'] ?? true);
		if (isset($data['pricing']) && $data['pricing'] !== false) {
			$generator->maybeCreate(ProductVariantPricing::class, ($data['pricing'] === true ? [] : $data['pricing']) + ['variant' => $productVariant]);
		}

		return $productVariant;
	}


	public function createProductVariantPricing(array $data, EntityGenerator $generator): ProductVariantPricing
	{
		$this->verifyData(['variant', 'pricingGroup', 'cents'], $data);
		$variant = $generator->maybeCreate(ProductVariant::class, $data['variant'] ?? []);
		$pricingGroup = $generator->maybeCreate(ProductPricingGroup::class, $data['pricingGroup'] ?? []);
		$cents = $data['cents'] ?? 10000;

		return new ProductVariantPricing($pricingGroup, $variant, $cents, null);
	}


	public static function pricingFor(ProductVariant $variant, ProductPricingGroup $pricingGroup = null): array
	{
		return [ProductVariantPricing::class, ['variant' => $variant, 'pricingGroup' => $pricingGroup]];
	}


	public function createProductPricingGroup(array $data, EntityGenerator $generator): ProductPricingGroup
	{
		$this->verifyData(['currency'], $data);

		$currency = $generator->maybeCreate(Currency::class, $data['currency'] ?? true);
		assert($currency instanceof Currency);

		return new ProductPricingGroup('default', $currency);
	}
}
