<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ChannelsMapper getMapper()
 * @method Channel hydrateEntity(array $data)
 * @method Channel attach(Channel $entity)
 * @method void detach(Channel $entity)
 * @method Channel|NULL getBy(array $conds)
 * @method Channel|NULL getById(int $primaryValue)
 * @method ICollection|Channel[] findAll()
 * @method ICollection|Channel[] findBy(array $where)
 * @method ICollection|Channel[] findById(int [] $primaryValues)
 * @method Channel|NULL persist(Channel $entity, bool $withCascade = true)
 * @method Channel|NULL persistAndFlush(Channel $entity, bool $withCascade = true)
 * @method Channel remove(int|Channel $entity, bool $withCascade = true)
 * @method Channel removeAndFlush(int|Channel $entity, bool $withCascade = true)
 */
class ChannelsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Channel::class];
	}
}
