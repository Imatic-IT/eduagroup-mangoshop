<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method PaymentStatesMapper getMapper()
 * @method PaymentState hydrateEntity(array $data)
 * @method PaymentState attach(PaymentState $entity)
 * @method void detach(PaymentState $entity)
 * @method PaymentState|NULL getBy(array $conds)
 * @method PaymentState|NULL getById(int $primaryValue)
 * @method ICollection|PaymentState[] findAll()
 * @method ICollection|PaymentState[] findBy(array $where)
 * @method ICollection|PaymentState[] findById(int [] $primaryValues)
 * @method PaymentState|NULL persist(PaymentState $entity, bool $withCascade = true)
 * @method PaymentState|NULL persistAndFlush(PaymentState $entity, bool $withCascade = true)
 * @method PaymentState remove(int|PaymentState $entity, bool $withCascade = true)
 * @method PaymentState removeAndFlush(int|PaymentState $entity, bool $withCascade = true)
 */
class PaymentStatesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [PaymentState::class];
	}
}
