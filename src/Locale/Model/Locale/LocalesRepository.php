<?php declare(strict_types = 1);

namespace MangoShop\Locale\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method LocalesMapper getMapper()
 * @method Locale hydrateEntity(array $data)
 * @method Locale attach(Locale $entity)
 * @method void detach(Locale $entity)
 * @method Locale|NULL getBy(array $conds)
 * @method Locale|NULL getById(int $primaryValue)
 * @method ICollection|Locale[] findAll()
 * @method ICollection|Locale[] findBy(array $where)
 * @method ICollection|Locale[] findById(int [] $primaryValues)
 * @method Locale|NULL persist(Locale $entity, bool $withCascade = true)
 * @method Locale|NULL persistAndFlush(Locale $entity, bool $withCascade = true)
 * @method Locale remove(int|Locale $entity, bool $withCascade = true)
 * @method Locale removeAndFlush(int|Locale $entity, bool $withCascade = true)
 */
class LocalesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Locale::class];
	}
}
