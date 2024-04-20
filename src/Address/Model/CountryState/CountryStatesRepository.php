<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CountryStatesMapper getMapper()
 * @method CountryState hydrateEntity(array $data)
 * @method CountryState attach(CountryState $entity)
 * @method void detach(CountryState $entity)
 * @method CountryState|NULL getBy(array $conds)
 * @method CountryState|NULL getById(int $primaryValue)
 * @method ICollection|CountryState[] findAll()
 * @method ICollection|CountryState[] findBy(array $where)
 * @method ICollection|CountryState[] findById(int [] $primaryValues)
 * @method CountryState|NULL persist(CountryState $entity, bool $withCascade = true)
 * @method CountryState|NULL persistAndFlush(CountryState $entity, bool $withCascade = true)
 * @method CountryState remove(int|CountryState $entity, bool $withCascade = true)
 * @method CountryState removeAndFlush(int|CountryState $entity, bool $withCascade = true)
 */
class CountryStatesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [CountryState::class];
	}
}
