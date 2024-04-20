<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrderPromotionsMapper getMapper()
 * @method OrderPromotion hydrateEntity(array $data)
 * @method OrderPromotion attach(OrderPromotion $entity)
 * @method void detach(OrderPromotion $entity)
 * @method OrderPromotion|NULL getBy(array $conds)
 * @method OrderPromotion|NULL getById(int $primaryValue)
 * @method ICollection|OrderPromotion[] findAll()
 * @method ICollection|OrderPromotion[] findBy(array $where)
 * @method ICollection|OrderPromotion[] findById(int [] $primaryValues)
 * @method OrderPromotion|NULL persist(OrderPromotion $entity, bool $withCascade = true)
 * @method OrderPromotion|NULL persistAndFlush(OrderPromotion $entity, bool $withCascade = true)
 * @method OrderPromotion remove(int|OrderPromotion $entity, bool $withCascade = true)
 * @method OrderPromotion removeAndFlush(int|OrderPromotion $entity, bool $withCascade = true)
 */
class OrderPromotionsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [OrderPromotion::class];
	}
}
