<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;
use Mangoweb\Clock\Clock;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string                                                   $code
 * @property-read Product                                                  $product      {m:1 Product::$variants}
 * @property-read bool                                                     $enabled
 * @property-read DateTimeImmutable                                        $createdAt
 *
 * @property-read ProductVariantPricingsCollection|ProductVariantPricing[] $pricings     {1:m ProductVariantPricing::$productVariant}
 * @property-read ICollection|ProductVariantTranslation[]                  $translations {1:m ProductVariantTranslation::$productVariant}
 */
class ProductVariant extends Entity
{
	public function __construct(string $code, Product $product)
	{
		parent::__construct();
		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('product', $product);
		$this->setReadOnlyValue('enabled', false);
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	public function setEnabled(bool $enabled = true): void
	{
		$this->setReadOnlyValue('enabled', $enabled);
	}


	public function getTranslation(Locale $locale): ?ProductVariantTranslation
	{
		$translation = $this->translations->getBy(['locale' => $locale]);
		assert($translation === null || $translation instanceof ProductVariantTranslation);

		return $translation;
	}


	public function getPricingIn(ProductPricingGroup $pricingGroup): ?ProductVariantPricing
	{
		return $this->pricings->getBy([
			'productPricingGroup' => $pricingGroup,
		]);
	}
}
