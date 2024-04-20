<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ProductPricingGroupsMapper getMapper()
 * @method ProductPricingGroup hydrateEntity(array $data)
 * @method ProductPricingGroup attach(ProductPricingGroup $entity)
 * @method void detach(ProductPricingGroup $entity)
 * @method ProductPricingGroup|NULL getBy(array $conds)
 * @method ProductPricingGroup|NULL getById(int $primaryValue)
 * @method ICollection|ProductPricingGroup[] findAll()
 * @method ICollection|ProductPricingGroup[] findBy(array $where)
 * @method ICollection|ProductPricingGroup[] findById(int [] $primaryValues)
 * @method ProductPricingGroup|NULL persist(ProductPricingGroup $entity, bool $withCascade = true)
 * @method ProductPricingGroup|NULL persistAndFlush(ProductPricingGroup $entity, bool $withCascade = true)
 * @method ProductPricingGroup remove(int|ProductPricingGroup $entity, bool $withCascade = true)
 * @method ProductPricingGroup removeAndFlush(int|ProductPricingGroup $entity, bool $withCascade = true)
 */
class ProductPricingGroupsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ProductPricingGroup::class];
	}
}
