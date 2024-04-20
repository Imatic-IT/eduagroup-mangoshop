<?php declare(strict_types = 1);

namespace MangoShop\Promotion\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method PromotionsMapper getMapper()
 * @method Promotion hydrateEntity(array $data)
 * @method Promotion attach(Promotion $entity)
 * @method void detach(Promotion $entity)
 * @method Promotion|NULL getBy(array $conds)
 * @method Promotion|NULL getById(int $primaryValue)
 * @method ICollection|Promotion[] findAll()
 * @method ICollection|Promotion[] findBy(array $where)
 * @method ICollection|Promotion[] findById(int [] $primaryValues)
 * @method Promotion|NULL persist(Promotion $entity, bool $withCascade = true)
 * @method Promotion|NULL persistAndFlush(Promotion $entity, bool $withCascade = true)
 * @method Promotion remove(int|Promotion $entity, bool $withCascade = true)
 * @method Promotion removeAndFlush(int|Promotion $entity, bool $withCascade = true)
 */
class PromotionsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Promotion::class];
	}
}
