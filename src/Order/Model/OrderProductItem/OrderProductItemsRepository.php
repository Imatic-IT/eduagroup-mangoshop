<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrderProductItemsMapper getMapper()
 * @method OrderProductItem hydrateEntity(array $data)
 * @method OrderProductItem attach(OrderProductItem $entity)
 * @method void detach(OrderProductItem $entity)
 * @method OrderProductItem|NULL getBy(array $conds)
 * @method OrderProductItem|NULL getById(int $primaryValue)
 * @method ICollection|OrderProductItem[] findAll()
 * @method ICollection|OrderProductItem[] findBy(array $where)
 * @method ICollection|OrderProductItem[] findById(int [] $primaryValues)
 * @method OrderProductItem|NULL persist(OrderProductItem $entity, bool $withCascade = true)
 * @method OrderProductItem|NULL persistAndFlush(OrderProductItem $entity, bool $withCascade = true)
 * @method OrderProductItem remove(int|OrderProductItem $entity, bool $withCascade = true)
 * @method OrderProductItem removeAndFlush(int|OrderProductItem $entity, bool $withCascade = true)
 */
class OrderProductItemsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [OrderProductItem::class];
	}
}
