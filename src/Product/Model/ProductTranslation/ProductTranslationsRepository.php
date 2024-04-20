<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ProductTranslationsMapper getMapper()
 * @method ProductTranslation hydrateEntity(array $data)
 * @method ProductTranslation attach(ProductTranslation $entity)
 * @method void detach(ProductTranslation $entity)
 * @method ProductTranslation|NULL getBy(array $conds)
 * @method ProductTranslation|NULL getById(int $primaryValue)
 * @method ICollection|ProductTranslation[] findAll()
 * @method ICollection|ProductTranslation[] findBy(array $where)
 * @method ICollection|ProductTranslation[] findById(int [] $primaryValues)
 * @method ProductTranslation|NULL persist(ProductTranslation $entity, bool $withCascade = true)
 * @method ProductTranslation|NULL persistAndFlush(ProductTranslation $entity, bool $withCascade = true)
 * @method ProductTranslation remove(int|ProductTranslation $entity, bool $withCascade = true)
 * @method ProductTranslation removeAndFlush(int|ProductTranslation $entity, bool $withCascade = true)
 */
class ProductTranslationsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ProductTranslation::class];
	}
}
