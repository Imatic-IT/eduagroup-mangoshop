<?php declare(strict_types = 1);

namespace MangoShop\Product\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method ProductVariantTranslationsMapper getMapper()
 * @method ProductVariantTranslation hydrateEntity(array $data)
 * @method ProductVariantTranslation attach(ProductVariantTranslation $entity)
 * @method void detach(ProductVariantTranslation $entity)
 * @method ProductVariantTranslation|NULL getBy(array $conds)
 * @method ProductVariantTranslation|NULL getById(int $primaryValue)
 * @method ICollection|ProductVariantTranslation[] findAll()
 * @method ICollection|ProductVariantTranslation[] findBy(array $where)
 * @method ICollection|ProductVariantTranslation[] findById(int [] $primaryValues)
 * @method ProductVariantTranslation|NULL persist(ProductVariantTranslation $entity, bool $withCascade = true)
 * @method ProductVariantTranslation|NULL persistAndFlush(ProductVariantTranslation $entity, bool $withCascade = true)
 * @method ProductVariantTranslation remove(int|ProductVariantTranslation $entity, bool $withCascade = true)
 * @method ProductVariantTranslation removeAndFlush(int|ProductVariantTranslation $entity, bool $withCascade = true)
 */
class ProductVariantTranslationsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ProductVariantTranslation::class];
	}
}
