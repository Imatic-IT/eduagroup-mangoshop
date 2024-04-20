<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrderBillingInfoMapper getMapper()
 * @method OrderBillingInfo hydrateEntity(array $data)
 * @method OrderBillingInfo attach(OrderBillingInfo $entity)
 * @method void detach(OrderBillingInfo $entity)
 * @method OrderBillingInfo|NULL getBy(array $conds)
 * @method OrderBillingInfo|NULL getById(int $primaryValue)
 * @method ICollection|OrderBillingInfo[] findAll()
 * @method ICollection|OrderBillingInfo[] findBy(array $where)
 * @method ICollection|OrderBillingInfo[] findById(int [] $primaryValues)
 * @method OrderBillingInfo|NULL persist(OrderBillingInfo $entity, bool $withCascade = true)
 * @method OrderBillingInfo|NULL persistAndFlush(OrderBillingInfo $entity, bool $withCascade = true)
 * @method OrderBillingInfo remove(int|OrderBillingInfo $entity, bool $withCascade = true)
 * @method OrderBillingInfo removeAndFlush(int|OrderBillingInfo $entity, bool $withCascade = true)
 */
class OrderBillingInfoRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [OrderBillingInfo::class];
	}
}
