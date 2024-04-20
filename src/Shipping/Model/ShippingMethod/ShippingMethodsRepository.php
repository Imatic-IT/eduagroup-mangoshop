<?php declare(strict_types = 1);

namespace MangoShop\Shipping\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ShippingMethodsMapper getMapper()
 * @method ShippingMethod hydrateEntity(array $data)
 * @method ShippingMethod attach(ShippingMethod $entity)
 * @method void detach(ShippingMethod $entity)
 * @method ShippingMethod|NULL getBy(array $conds)
 * @method ShippingMethod|NULL getById(int $primaryValue)
 * @method ICollection|ShippingMethod[] findAll()
 * @method ICollection|ShippingMethod[] findBy(array $where)
 * @method ICollection|ShippingMethod[] findById(int [] $primaryValues)
 * @method ShippingMethod|NULL persist(ShippingMethod $entity, bool $withCascade = true)
 * @method ShippingMethod|NULL persistAndFlush(ShippingMethod $entity, bool $withCascade = true)
 * @method ShippingMethod remove(int|ShippingMethod $entity, bool $withCascade = true)
 * @method ShippingMethod removeAndFlush(int|ShippingMethod $entity, bool $withCascade = true)
 */
class ShippingMethodsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ShippingMethod::class];
	}
}
