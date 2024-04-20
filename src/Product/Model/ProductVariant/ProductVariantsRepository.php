<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ProductVariantsMapper getMapper()
 * @method ProductVariant hydrateEntity(array $data)
 * @method ProductVariant attach(ProductVariant $entity)
 * @method void detach(ProductVariant $entity)
 * @method ProductVariant|NULL getBy(array $conds)
 * @method ProductVariant|NULL getById(int $primaryValue)
 * @method ICollection|ProductVariant[] findAll()
 * @method ICollection|ProductVariant[] findBy(array $where)
 * @method ICollection|ProductVariant[] findById(int [] $primaryValues)
 * @method ProductVariant|NULL persist(ProductVariant $entity, bool $withCascade = true)
 * @method ProductVariant|NULL persistAndFlush(ProductVariant $entity, bool $withCascade = true)
 * @method ProductVariant remove(int|ProductVariant $entity, bool $withCascade = true)
 * @method ProductVariant removeAndFlush(int|ProductVariant $entity, bool $withCascade = true)
 */
class ProductVariantsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ProductVariant::class];
	}
}
