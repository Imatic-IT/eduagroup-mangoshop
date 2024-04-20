<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method CartProductItemsMapper getMapper()
 * @method CartProductItem hydrateEntity(array $data)
 * @method CartProductItem attach(CartProductItem $entity)
 * @method void detach(CartProductItem $entity)
 * @method CartProductItem|NULL getBy(array $conds)
 * @method CartProductItem|NULL getById(int $primaryValue)
 * @method ICollection|CartProductItem[] findAll()
 * @method ICollection|CartProductItem[] findBy(array $where)
 * @method ICollection|CartProductItem[] findById(int [] $primaryValues)
 * @method CartProductItem|NULL persist(CartProductItem $entity, bool $withCascade = true)
 * @method CartProductItem|NULL persistAndFlush(CartProductItem $entity, bool $withCascade = true)
 * @method CartProductItem remove(int|CartProductItem $entity, bool $withCascade = true)
 * @method CartProductItem removeAndFlush(int|CartProductItem $entity, bool $withCascade = true)
 */
class CartProductItemsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [CartProductItem::class];
	}
}
