<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CustomersMapper getMapper()
 * @method Customer hydrateEntity(array $data)
 * @method Customer attach(Customer $entity)
 * @method void detach(Customer $entity)
 * @method Customer|NULL getBy(array $conds)
 * @method Customer|NULL getById(int $primaryValue)
 * @method ICollection|Customer[] findAll()
 * @method ICollection|Customer[] findBy(array $where)
 * @method ICollection|Customer[] findById(int [] $primaryValues)
 * @method Customer|NULL persist(Customer $entity, bool $withCascade = true)
 * @method Customer|NULL persistAndFlush(Customer $entity, bool $withCascade = true)
 * @method Customer remove(int|Customer $entity, bool $withCascade = true)
 * @method Customer removeAndFlush(int|Customer $entity, bool $withCascade = true)
 */
class CustomersRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Customer::class];
	}
}
