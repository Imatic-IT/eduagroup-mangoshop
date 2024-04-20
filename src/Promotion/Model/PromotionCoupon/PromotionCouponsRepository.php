<?php declare(strict_types = 1);

namespace MangoShop\Promotion\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method PromotionCouponsMapper getMapper()
 * @method PromotionCoupon hydrateEntity(array $data)
 * @method PromotionCoupon attach(PromotionCoupon $entity)
 * @method void detach(PromotionCoupon $entity)
 * @method PromotionCoupon|NULL getBy(array $conds)
 * @method PromotionCoupon|NULL getById(int $primaryValue)
 * @method ICollection|PromotionCoupon[] findAll()
 * @method ICollection|PromotionCoupon[] findBy(array $where)
 * @method ICollection|PromotionCoupon[] findById(int [] $primaryValues)
 * @method PromotionCoupon|NULL persist(PromotionCoupon $entity, bool $withCascade = true)
 * @method PromotionCoupon|NULL persistAndFlush(PromotionCoupon $entity, bool $withCascade = true)
 * @method PromotionCoupon remove(int|PromotionCoupon $entity, bool $withCascade = true)
 * @method PromotionCoupon removeAndFlush(int|PromotionCoupon $entity, bool $withCascade = true)
 */
class PromotionCouponsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [PromotionCoupon::class];
	}
}
