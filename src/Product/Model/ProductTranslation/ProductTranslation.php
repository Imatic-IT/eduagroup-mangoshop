<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;


/**
 * @property-read Product $product {m:1 Product::$translations}
 * @property-read Locale  $locale {m:1 Locale, oneSided=true}
 * @property-read string  $name
 */
class ProductTranslation extends Entity
{
	public function __construct(Product $product, Locale $locale, string $name)
	{
		parent::__construct();
		$this->setReadOnlyValue('product', $product);
		$this->setReadOnlyValue('locale', $locale);
		$this->setReadOnlyValue('name', $name);
	}


	public function setName(string $name): void
	{
		$this->setReadOnlyValue('name', $name);
	}
}
