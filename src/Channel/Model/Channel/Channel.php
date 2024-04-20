<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Currency;
use MangoShop\Product\Model\ProductPricingGroup;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string               $code
 * @property-read string               $name
 * @property-read Locale               $defaultLocale       {m:1 Locale, oneSided=true}
 * @property-read ProductPricingGroup  $pricingGroup        {m:1 ProductPricingGroup, oneSided=true}
 * @property-read CheckoutOptionGroup  $checkoutOptionGroup {m:1 CheckoutOptionGroup, oneSided=true}
 * @property-read Currency             $currency            {virtual}
 *
 * @property-read ICollection|Locale[] $locales             {m:m Locale, isMain=true, oneSided=true}
 */
class Channel extends Entity
{
	public function __construct(
		string $code,
		string $name,
		Locale $defaultLocale,
		ProductPricingGroup $pricingGroup,
		CheckoutOptionGroup $checkoutOptionGroup
	) {
		parent::__construct();

		assert($pricingGroup->currency === $checkoutOptionGroup->currency);

		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('name', $name);
		$this->setReadOnlyValue('pricingGroup', $pricingGroup);
		$this->setReadOnlyValue('checkoutOptionGroup', $checkoutOptionGroup);

		$this->setLocales([$defaultLocale], $defaultLocale);
	}


	public function setName(string $name): void
	{
		$this->setReadOnlyValue('name', $name);
	}


	public function setDefaultLocale(Locale $defaultLocale): void
	{
		assert($this->hasLocale($defaultLocale));
		$this->setReadOnlyValue('defaultLocale', $defaultLocale);
	}


	public function setPricingGroup(ProductPricingGroup $pricingGroup): void
	{
		assert($pricingGroup->currency === $this->pricingGroup->currency);
		$this->setReadOnlyValue('pricingGroup', $pricingGroup);
	}


	public function setCheckoutOptionGroup(CheckoutOptionGroup $checkoutOptionGroup): void
	{
		assert($checkoutOptionGroup->currency === $this->checkoutOptionGroup->currency);
		$this->setReadOnlyValue('checkoutOptionGroup', $checkoutOptionGroup);
	}


	public function hasLocale(Locale $locale): bool
	{
		return $this->getRelationship('locales')->has($locale);
	}


	/**
	 * @param Locale[]|iterable $locales
	 * @param Locale            $defaultLocale
	 */
	public function setLocales(iterable $locales, Locale $defaultLocale): void
	{
		$includesDefaultLocale = false;
		foreach ($locales as $locale) {
			assert($locale instanceof Locale);
			if ($locale === $defaultLocale) {
				$includesDefaultLocale = true;
			}
		}

		assert($includesDefaultLocale);
		$this->getRelationship('locales')->set($locales instanceof \Traversable ? iterator_to_array($locales, false) : $locales);
		$this->setReadOnlyValue('defaultLocale', $defaultLocale);
	}


	public function getterCurrency(): Currency
	{
		return $this->pricingGroup->currency;
	}
}
