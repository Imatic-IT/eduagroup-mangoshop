<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CartPromotionsMapper getMapper()
 * @method CartPromotion hydrateEntity(array $data)
 * @method CartPromotion attach(CartPromotion $entity)
 * @method void detach(CartPromotion $entity)
 * @method CartPromotion|NULL getBy(array $conds)
 * @method CartPromotion|NULL getById(int $primaryValue)
 * @method ICollection|CartPromotion[] findAll()
 * @method ICollection|CartPromotion[] findBy(array $where)
 * @method ICollection|CartPromotion[] findById(int [] $primaryValues)
 * @method CartPromotion|NULL persist(CartPromotion $entity, bool $withCascade = true)
 * @method CartPromotion|NULL persistAndFlush(CartPromotion $entity, bool $withCascade = true)
 * @method CartPromotion remove(int|CartPromotion $entity, bool $withCascade = true)
 * @method CartPromotion removeAndFlush(int|CartPromotion $entity, bool $withCascade = true)
 */
class CartPromotionsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [CartPromotion::class];
	}
}
