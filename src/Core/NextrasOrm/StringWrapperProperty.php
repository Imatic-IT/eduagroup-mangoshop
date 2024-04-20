<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use MangoShop\Core\IStringWrapper;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;


class StringWrapperProperty extends ImmutableValuePropertyContainer
{
	/** @var string */
	private $wrapperClass;


	public function __construct(IEntity $entity, PropertyMetadata $propertyMetadata)
	{
		parent::__construct($entity, $propertyMetadata);
		assert(count($propertyMetadata->types) === 1);
		$this->wrapperClass = key($propertyMetadata->types);
		assert(class_exists($this->wrapperClass));
		assert(is_a($this->wrapperClass, IStringWrapper::class, true));
	}


	public function deserialize($value): ?IStringWrapper
	{
		$wrapperClass = $this->wrapperClass;
		return new $wrapperClass($value);
	}


	public function serialize($value)
	{
		assert($value instanceof IStringWrapper);
		return (string) $value;
	}
}
