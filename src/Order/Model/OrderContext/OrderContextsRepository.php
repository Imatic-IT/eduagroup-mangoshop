<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrderContextsMapper getMapper()
 * @method OrderContext hydrateEntity(array $data)
 * @method OrderContext attach(OrderContext $entity)
 * @method void detach(OrderContext $entity)
 * @method OrderContext|NULL getBy(array $conds)
 * @method OrderContext|NULL getById(int $primaryValue)
 * @method ICollection|OrderContext[] findAll()
 * @method ICollection|OrderContext[] findBy(array $where)
 * @method ICollection|OrderContext[] findById(int [] $primaryValues)
 * @method OrderContext|NULL persist(OrderContext $entity, bool $withCascade = true)
 * @method OrderContext|NULL persistAndFlush(OrderContext $entity, bool $withCascade = true)
 * @method OrderContext remove(int|OrderContext $entity, bool $withCascade = true)
 * @method OrderContext removeAndFlush(int|OrderContext $entity, bool $withCascade = true)
 */
class OrderContextsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [OrderContext::class];
	}
}
