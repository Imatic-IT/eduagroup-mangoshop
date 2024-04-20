<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;
use Mangoweb\Clock\Clock;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string                           $code
 * @property-read bool                             $enabled
 * @property-read DateTimeImmutable                $createdAt
 *
 * @property-read ICollection|ProductVariant[]     $variants     {1:m ProductVariant::$product}
 * @property-read ICollection|ProductTranslation[] $translations {1:m ProductTranslation::$product}
 */
class Product extends Entity
{
	public function __construct(string $code)
	{
		parent::__construct();
		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('enabled', false);
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	public function getTranslation(Locale $locale): ?ProductTranslation
	{
		$translation = $this->translations->getBy(['locale' => $locale]);
		assert($translation === null || $translation instanceof ProductTranslation);

		return $translation;
	}


	public function setEnabled(bool $enabled = true): void
	{
		$this->setReadOnlyValue('enabled', $enabled);
	}


	public function hasVariant(ProductVariant $productVariant): bool
	{
		return $this->getRelationship('variants')->has($productVariant);
	}
}
