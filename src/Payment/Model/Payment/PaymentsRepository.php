<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method PaymentsMapper getMapper()
 * @method Payment hydrateEntity(array $data)
 * @method Payment attach(Payment $entity)
 * @method void detach(Payment $entity)
 * @method Payment|NULL getBy(array $conds)
 * @method Payment|NULL getById(int $primaryValue)
 * @method ICollection|Payment[] findAll()
 * @method ICollection|Payment[] findBy(array $where)
 * @method ICollection|Payment[] findById(int [] $primaryValues)
 * @method Payment|NULL persist(Payment $entity, bool $withCascade = true)
 * @method Payment|NULL persistAndFlush(Payment $entity, bool $withCascade = true)
 * @method Payment remove(int|Payment $entity, bool $withCascade = true)
 * @method Payment removeAndFlush(int|Payment $entity, bool $withCascade = true)
 */
class PaymentsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Payment::class];
	}
}
