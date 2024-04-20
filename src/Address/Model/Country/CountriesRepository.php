<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CountriesMapper getMapper()
 * @method Country hydrateEntity(array $data)
 * @method Country attach(Country $entity)
 * @method void detach(Country $entity)
 * @method Country|NULL getBy(array $conds)
 * @method Country|NULL getById(int $primaryValue)
 * @method ICollection|Country[] findAll()
 * @method ICollection|Country[] findBy(array $where)
 * @method ICollection|Country[] findById(int [] $primaryValues)
 * @method Country|NULL persist(Country $entity, bool $withCascade = true)
 * @method Country|NULL persistAndFlush(Country $entity, bool $withCascade = true)
 * @method Country remove(int|Country $entity, bool $withCascade = true)
 * @method Country removeAndFlush(int|Country $entity, bool $withCascade = true)
 */
class CountriesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Country::class];
	}
}
