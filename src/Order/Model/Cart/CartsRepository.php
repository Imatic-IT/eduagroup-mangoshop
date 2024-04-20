<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CartsMapper getMapper()
 * @method Cart hydrateEntity(array $data)
 * @method Cart attach(Cart $entity)
 * @method void detach(Cart $entity)
 * @method Cart|NULL getBy(array $conds)
 * @method Cart|NULL getById(int $primaryValue)
 * @method ICollection|Cart[] findAll()
 * @method ICollection|Cart[] findBy(array $where)
 * @method ICollection|Cart[] findById(int [] $primaryValues)
 * @method Cart|NULL persist(Cart $entity, bool $withCascade = true)
 * @method Cart|NULL persistAndFlush(Cart $entity, bool $withCascade = true)
 * @method Cart remove(int|Cart $entity, bool $withCascade = true)
 * @method Cart removeAndFlush(int|Cart $entity, bool $withCascade = true)
 */
class CartsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Cart::class];
	}
}
