<?php declare(strict_types = 1);

namespace MangoShopTests\PhpStan;

use MangoShopTests\EntityGenerator;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;


class EntityGeneratorMethodsTypeExtension implements DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return EntityGenerator::class;
	}


	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return in_array($methodReflection->getName(), ['save', 'reload', 'create'], TRUE);
	}


	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		if (!isset($methodCall->args[0])) {
			return $methodReflection->getReturnType();
		}
		$expr = $methodCall->args[0]->value;
		if ($methodReflection->getName() === 'create') {
			if ($expr instanceof ClassConstFetch && $expr->name === 'class' && $expr->class instanceof FullyQualified) {
				return new ObjectType((string) $expr->class);
			}
			return $methodReflection->getReturnType();
		}
		return $scope->getType($methodCall->args[0]->value);
	}

}
