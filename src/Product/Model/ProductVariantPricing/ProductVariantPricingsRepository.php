<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ProductVariantPricingsMapper getMapper()
 * @method ProductVariantPricing hydrateEntity(array $data)
 * @method ProductVariantPricing attach(ProductVariantPricing $entity)
 * @method void detach(ProductVariantPricing $entity)
 * @method ProductVariantPricing|NULL getBy(array $conds)
 * @method ProductVariantPricing|NULL getById(int $primaryValue)
 * @method ICollection|ProductVariantPricing[] findAll()
 * @method ICollection|ProductVariantPricing[] findBy(array $where)
 * @method ICollection|ProductVariantPricing[] findById(int [] $primaryValues)
 * @method ProductVariantPricing|NULL persist(ProductVariantPricing $entity, bool $withCascade = true)
 * @method ProductVariantPricing|NULL persistAndFlush(ProductVariantPricing $entity, bool $withCascade = true)
 * @method ProductVariantPricing remove(int|ProductVariantPricing $entity, bool $withCascade = true)
 * @method ProductVariantPricing removeAndFlush(int|ProductVariantPricing $entity, bool $withCascade = true)
 */
class ProductVariantPricingsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ProductVariantPricing::class];
	}
}
