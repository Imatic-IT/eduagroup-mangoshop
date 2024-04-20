<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CheckoutOptionGroupMapper getMapper()
 * @method CheckoutOptionGroup hydrateEntity(array $data)
 * @method CheckoutOptionGroup attach(CheckoutOptionGroup $entity)
 * @method void detach(CheckoutOptionGroup $entity)
 * @method CheckoutOptionGroup|NULL getBy(array $conds)
 * @method CheckoutOptionGroup|NULL getById(int $primaryValue)
 * @method ICollection|CheckoutOptionGroup[] findAll()
 * @method ICollection|CheckoutOptionGroup[] findBy(array $where)
 * @method ICollection|CheckoutOptionGroup[] findById(int [] $primaryValues)
 * @method CheckoutOptionGroup|NULL persist(CheckoutOptionGroup $entity, bool $withCascade = true)
 * @method CheckoutOptionGroup|NULL persistAndFlush(CheckoutOptionGroup $entity, bool $withCascade = true)
 * @method CheckoutOptionGroup remove(int|CheckoutOptionGroup $entity, bool $withCascade = true)
 * @method CheckoutOptionGroup removeAndFlush(int|CheckoutOptionGroup $entity, bool $withCascade = true)
 */
class CheckoutOptionGroupRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [CheckoutOptionGroup::class];
	}
}
