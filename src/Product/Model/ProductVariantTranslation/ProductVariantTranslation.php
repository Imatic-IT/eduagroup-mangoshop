<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;


/**
 * @property-read ProductVariant $productVariant {m:1 ProductVariant::$translations}
 * @property-read Locale         $locale         {m:1 Locale, oneSided=true}
 * @property-read string         $name
 */
class ProductVariantTranslation extends Entity
{
	public function __construct(ProductVariant $productVariant, Locale $locale, string $name)
	{
		parent::__construct();
		$this->setReadOnlyValue('productVariant', $productVariant);
		$this->setReadOnlyValue('locale', $locale);
		$this->setReadOnlyValue('name', $name);
	}


	public function setName(string $name): void
	{
		$this->setReadOnlyValue('name', $name);
	}
}
