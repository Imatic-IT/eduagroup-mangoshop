<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method SessionsMapper getMapper()
 * @method Session hydrateEntity(array $data)
 * @method Session attach(Session $entity)
 * @method void detach(Session $entity)
 * @method Session|NULL getBy(array $conds)
 * @method Session|NULL getById(int $primaryValue)
 * @method ICollection|Session[] findAll()
 * @method ICollection|Session[] findBy(array $where)
 * @method ICollection|Session[] findById(int [] $primaryValues)
 * @method Session|NULL persist(Session $entity, bool $withCascade = true)
 * @method Session|NULL persistAndFlush(Session $entity, bool $withCascade = true)
 * @method Session remove(int|Session $entity, bool $withCascade = true)
 * @method Session removeAndFlush(int|Session $entity, bool $withCascade = true)
 */
class SessionsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Session::class];
	}
}
