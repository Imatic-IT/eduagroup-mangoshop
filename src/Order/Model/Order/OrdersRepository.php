<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrdersMapper getMapper()
 * @method Order hydrateEntity(array $data)
 * @method Order attach(Order $entity)
 * @method void detach(Order $entity)
 * @method Order|NULL getBy(array $conds)
 * @method Order|NULL getById(int $primaryValue)
 * @method ICollection|Order[] findAll()
 * @method ICollection|Order[] findBy(array $where)
 * @method ICollection|Order[] findById(int [] $primaryValues)
 * @method Order|NULL persist(Order $entity, bool $withCascade = true)
 * @method Order|NULL persistAndFlush(Order $entity, bool $withCascade = true)
 * @method Order remove(int|Order $entity, bool $withCascade = true)
 * @method Order removeAndFlush(int|Order $entity, bool $withCascade = true)
 */
class OrdersRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Order::class];
	}
}
