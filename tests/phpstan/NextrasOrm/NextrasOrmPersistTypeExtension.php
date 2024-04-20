<?php declare(strict_types = 1);

namespace MangoShopTests\PhpStan\NextrasOrm;

use Nextras\Orm\Model\Model;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;


class NextrasOrmPersistTypeExtension implements DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return Model::class;
	}


	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return in_array($methodReflection->getName(), ['persist', 'persistAndFlush'], TRUE);
	}


	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		if (!isset($methodCall->args[0])) {
			return $methodReflection->getReturnType();
		}
		return $scope->getType($methodCall->args[0]->value);
	}

}
