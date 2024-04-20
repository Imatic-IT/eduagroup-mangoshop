<?php declare(strict_types = 1);

namespace MangoShop\Money\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CurrenciesMapper getMapper()
 * @method Currency hydrateEntity(array $data)
 * @method Currency attach(Currency $entity)
 * @method void detach(Currency $entity)
 * @method Currency|NULL getBy(array $conds)
 * @method Currency|NULL getById(int $primaryValue)
 * @method ICollection|Currency[] findAll()
 * @method ICollection|Currency[] findBy(array $where)
 * @method ICollection|Currency[] findById(int [] $primaryValues)
 * @method Currency|NULL persist(Currency $entity, bool $withCascade = true)
 * @method Currency|NULL persistAndFlush(Currency $entity, bool $withCascade = true)
 * @method Currency remove(int|Currency $entity, bool $withCascade = true)
 * @method Currency removeAndFlush(int|Currency $entity, bool $withCascade = true)
 */
class CurrenciesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Currency::class];
	}
}
