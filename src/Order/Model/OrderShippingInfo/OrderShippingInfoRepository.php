<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrderShippingInfoMapper getMapper()
 * @method OrderShippingInfo hydrateEntity(array $data)
 * @method OrderShippingInfo attach(OrderShippingInfo $entity)
 * @method void detach(OrderShippingInfo $entity)
 * @method OrderShippingInfo|NULL getBy(array $conds)
 * @method OrderShippingInfo|NULL getById(int $primaryValue)
 * @method ICollection|OrderShippingInfo[] findAll()
 * @method ICollection|OrderShippingInfo[] findBy(array $where)
 * @method ICollection|OrderShippingInfo[] findById(int [] $primaryValues)
 * @method OrderShippingInfo|NULL persist(OrderShippingInfo $entity, bool $withCascade = true)
 * @method OrderShippingInfo|NULL persistAndFlush(OrderShippingInfo $entity, bool $withCascade = true)
 * @method OrderShippingInfo remove(int|OrderShippingInfo $entity, bool $withCascade = true)
 * @method OrderShippingInfo removeAndFlush(int|OrderShippingInfo $entity, bool $withCascade = true)
 */
class OrderShippingInfoRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [OrderShippingInfo::class];
	}
}
