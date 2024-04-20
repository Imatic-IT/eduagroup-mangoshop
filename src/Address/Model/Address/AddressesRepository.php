<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method AddressesMapper getMapper()
 * @method Address hydrateEntity(array $data)
 * @method Address attach(Address $entity)
 * @method void detach(Address $entity)
 * @method Address|NULL getBy(array $conds)
 * @method Address|NULL getById(int $primaryValue)
 * @method ICollection|Address[] findAll()
 * @method ICollection|Address[] findBy(array $where)
 * @method ICollection|Address[] findById(int [] $primaryValues)
 * @method Address|NULL persist(Address $entity, bool $withCascade = true)
 * @method Address|NULL persistAndFlush(Address $entity, bool $withCascade = true)
 * @method Address remove(int|Address $entity, bool $withCascade = true)
 * @method Address removeAndFlush(int|Address $entity, bool $withCascade = true)
 */
class AddressesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Address::class];
	}
}
