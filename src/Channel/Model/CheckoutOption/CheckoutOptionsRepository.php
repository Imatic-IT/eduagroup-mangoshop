<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CheckoutOptionsMapper getMapper()
 * @method CheckoutOption hydrateEntity(array $data)
 * @method CheckoutOption attach(CheckoutOption $entity)
 * @method void detach(CheckoutOption $entity)
 * @method CheckoutOption|NULL getBy(array $conds)
 * @method CheckoutOption|NULL getById(int $primaryValue)
 * @method ICollection|CheckoutOption[] findAll()
 * @method ICollection|CheckoutOption[] findBy(array $where)
 * @method ICollection|CheckoutOption[] findById(int [] $primaryValues)
 * @method CheckoutOption|NULL persist(CheckoutOption $entity, bool $withCascade = true)
 * @method CheckoutOption|NULL persistAndFlush(CheckoutOption $entity, bool $withCascade = true)
 * @method CheckoutOption remove(int|CheckoutOption $entity, bool $withCascade = true)
 * @method CheckoutOption removeAndFlush(int|CheckoutOption $entity, bool $withCascade = true)
 */
class CheckoutOptionsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [CheckoutOption::class];
	}
}
