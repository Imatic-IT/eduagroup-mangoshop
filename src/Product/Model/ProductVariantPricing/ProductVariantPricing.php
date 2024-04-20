<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Currency;
use MangoShop\Money\Model\Money;
use Mangoweb\Clock\Clock;


/**
 * @property-read null|ProductVariantPricing $previousVersion             {1:1 ProductVariantPricing::$nextVersion, isMain=true}
 * @property-read null|ProductVariantPricing $nextVersion                 {1:1 ProductVariantPricing::$previousVersion}
 * @property-read null|ProductPricingGroup   $productPricingGroup         {m:1 ProductPricingGroup::$pricings}
 * @property-read ProductVariant             $productVariant              {m:1 ProductVariant::$pricings}
 * @property-read Currency                   $currency                    {virtual}
 * @property-read int                        $priceCents
 * @property-read Money                      $price                       {virtual}
 * @property-read null|int                   $originalPriceCents
 * @property-read null|Money                 $originalPrice               {virtual}
 * @property-read DateTimeImmutable          $createdAt
 */
class ProductVariantPricing extends Entity
{
	public function __construct(
		ProductPricingGroup $productPricingGroup,
		ProductVariant $productVariant,
		int $priceCent,
		?int $originalPriceCents
	) {
		parent::__construct();
		$this->setReadOnlyValue('productPricingGroup', $productPricingGroup);
		$this->setReadOnlyValue('productVariant', $productVariant);
		$this->setReadOnlyValue('priceCents', $priceCent);
		$this->setReadOnlyValue('originalPriceCents', $originalPriceCents);
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	public function withPrice(int $priceCent, ?int $originalPriceCents): self
	{
		assert($this->productPricingGroup !== null);

		$newPricing = new self(
			$this->productPricingGroup,
			$this->productVariant,
			$priceCent,
			$originalPriceCents
		);

		$this->setReadOnlyValue('productPricingGroup', null);
		$newPricing->setReadOnlyValue('previousVersion', $this);

		return $newPricing;
	}


	public function getterCurrency(): Currency
	{
		$pricing = $this;
		while ($pricing->nextVersion) {
			$pricing = $pricing->nextVersion;
		}
		assert($pricing !== null);
		assert($pricing->productPricingGroup !== null);
		return $pricing->productPricingGroup->currency;
	}


	public function getterPrice(): Money
	{
		return new Money($this->priceCents, $this->currency);
	}


	public function getterOriginalPrice(): ?Money
	{
		if (!$this->originalPriceCents) {
			return null;
		}
		return new Money($this->originalPriceCents, $this->currency);
	}
}
