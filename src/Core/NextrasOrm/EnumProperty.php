<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use MabeEnum\Enum;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;


class EnumProperty extends ImmutableValuePropertyContainer
{
	/** @var string */
	private $enumClass;


	public function __construct(IEntity $entity, PropertyMetadata $propertyMetadata)
	{
		parent::__construct($entity, $propertyMetadata);
		assert(count($propertyMetadata->types) === 1);
		$this->enumClass = key($propertyMetadata->types);
		assert(class_exists($this->enumClass));
		assert(is_a($this->enumClass, Enum::class, true));
	}


	public function deserialize($value): ?Enum
	{
		return ($this->enumClass)::byValue($value);
	}


	public function serialize($value)
	{
		assert($value instanceof Enum);
		return $value->getValue();
	}
}
