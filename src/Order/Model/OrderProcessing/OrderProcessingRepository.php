<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method OrderProcessingMapper getMapper()
 * @method OrderProcessing hydrateEntity(array $data)
 * @method OrderProcessing attach(OrderProcessing $entity)
 * @method void detach(OrderProcessing $entity)
 * @method OrderProcessing|NULL getBy(array $conds)
 * @method OrderProcessing|NULL getById(int $primaryValue)
 * @method ICollection|OrderProcessing[] findAll()
 * @method ICollection|OrderProcessing[] findBy(array $where)
 * @method ICollection|OrderProcessing[] findById(int [] $primaryValues)
 * @method OrderProcessing|NULL persist(OrderProcessing $entity, bool $withCascade = true)
 * @method OrderProcessing|NULL persistAndFlush(OrderProcessing $entity, bool $withCascade = true)
 * @method OrderProcessing remove(int | OrderProcessing $entity, bool $withCascade = true)
 * @method OrderProcessing removeAndFlush(int | OrderProcessing $entity, bool $withCascade = true)
 */
class OrderProcessingRepository extends Repository
{
	public function setEntityClassName(string $entityClassName): void
	{
		$this->entityClassName = $entityClassName;
	}


	public static function getEntityClassNames(): array
	{
		return array_merge([OrderProcessing::class], self::getDefinedEntityClassNames(self::class));
	}
}
