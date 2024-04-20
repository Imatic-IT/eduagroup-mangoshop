<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ProductsMapper getMapper()
 * @method Product hydrateEntity(array $data)
 * @method Product attach(Product $entity)
 * @method void detach(Product $entity)
 * @method Product|NULL getBy(array $conds)
 * @method Product|NULL getById(int $primaryValue)
 * @method ICollection|Product[] findAll()
 * @method ICollection|Product[] findBy(array $where)
 * @method ICollection|Product[] findById(int [] $primaryValues)
 * @method Product|NULL persist(Product $entity, bool $withCascade = true)
 * @method Product|NULL persistAndFlush(Product $entity, bool $withCascade = true)
 * @method Product remove(int|Product $entity, bool $withCascade = true)
 * @method Product removeAndFlush(int|Product $entity, bool $withCascade = true)
 */
class ProductsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Product::class];
	}
}
