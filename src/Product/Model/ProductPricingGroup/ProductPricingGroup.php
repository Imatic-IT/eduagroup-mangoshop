<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Currency;


/**
 * @property-read string                                                   $name
 * @property-read Currency                                                 $currency  {m:1 Currency, oneSided=true}
 *
 * @property-read ProductVariantPricingsCollection|ProductVariantPricing[] $pricings  {1:m ProductVariantPricing::$productPricingGroup}
 */
class ProductPricingGroup extends Entity
{
	public function __construct(string $name, Currency $currency)
	{
		parent::__construct();
		$this->setReadOnlyValue('name', $name);
		$this->setReadOnlyValue('currency', $currency);
	}


	public function setName(string $name): void
	{
		$this->setReadOnlyValue('name', $name);
	}


	public function getPricingFor(ProductVariant $productVariant): ?ProductVariantPricing
	{
		return $this->pricings->getBy([
			'productVariant' => $productVariant,
		]);
	}


	public function setPricingFor(ProductVariant $productVariant, int $priceCent, ?int $originalPriceCents): ProductVariantPricing
	{
		$currentPricing = $this->getPricingFor($productVariant);

		if ($currentPricing !== null) {
			$newPricing = $currentPricing->withPrice($priceCent, $originalPriceCents);

		} else {
			$newPricing = new ProductVariantPricing($this, $productVariant, $priceCent, $originalPriceCents);
		}

		assert($newPricing->productPricingGroup === $this);
		return $newPricing;
	}
}
