<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nextras\Orm\Entity\Entity as BaseEntity;
use Nextras\Orm\Relationships\IRelationshipCollection;

/**
 * @property-read int $id    {primary}
 */
abstract class Entity extends BaseEntity
{
	public function &__get($name)
	{
		$value = $this->getValue($name);
		if ($value instanceof IRelationshipCollection) {
			$value = $value->get();
		}
		return $value;
	}


	protected function getRelationship(string $name): IRelationshipCollection
	{
		$value = $this->getValue($name);
		assert($value instanceof IRelationshipCollection);
		return $value;
	}
}
