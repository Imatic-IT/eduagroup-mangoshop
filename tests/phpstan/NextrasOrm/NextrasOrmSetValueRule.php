<?php declare(strict_types = 1);

namespace MangoShopTests\PhpStan\NextrasOrm;

use Nextras\Orm\Entity\Entity;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;


class NextrasOrmSetValueRule implements Rule
{

	/** @var Broker */
	private $broker;


	public function __construct(Broker $broker)
	{
		$this->broker = $broker;
	}


	public function getNodeType(): string
	{
		return MethodCall::class;
	}


	/**
	 * @param \PhpParser\Node\Expr\MethodCall $node
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		if (!is_string($node->name) || !in_array($node->name, ['setValue', 'setReadOnlyValue'], TRUE)) {
			return [];
		}

		$args = $node->args;
		if (!isset($args[0], $args[1])) {
			return [];
		}
		$valueType = $scope->getType($args[1]->value);
		$varType = $scope->getType($node->var);

		if (!($varType->getReferencedClasses())) {
			return [];
		}
		$firstValue = $args[0]->value;
		if (!$firstValue instanceof Node\Scalar\String_) {
			return [];
		}
		$fieldName = $firstValue->value;

		$class = $this->broker->getClass($varType->getReferencedClasses()[0]);
		if (!in_array(Entity::class, $class->getParentClassesNames(), TRUE)) {
			return [];
		}

		if (!$class->hasProperty($fieldName)) {
			return [sprintf('Entity %s has no field named %s', $varType->getReferencedClasses()[0], $fieldName)];
		}

		$property = $class->getProperty($fieldName, $scope);
		$propertyType = $property->getType();
		if (!$propertyType->accepts($valueType)) {
			return [sprintf('Entity %s: field $%s (%s) does not accept %s', $varType->getReferencedClasses()[0], $fieldName, $propertyType->describe(), $valueType->describe())];
		}


		if ($node->name === 'setReadOnlyValue' && (!$scope->isInClass() || !$scope->hasVariableType('this') || !$varType->accepts($scope->getVariableType('this')))) {
			return [sprintf('You cannot set readonly field $%s on entity %s', $fieldName, $varType->getReferencedClasses()[0])];
		}
		return [];
	}

}
