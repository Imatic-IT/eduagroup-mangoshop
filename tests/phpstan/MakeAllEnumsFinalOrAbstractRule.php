<?php declare(strict_types = 1);

namespace MangoShopTests\PhpStan;

use MabeEnum\Enum;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;


class MakeAllEnumsFinalOrAbstractRule implements Rule
{

	/**
	 * @return string Class implementing \PhpParser\Node
	 */
	public function getNodeType(): string
	{
		return Class_::class;
	}


	/**
	 * @param \PhpParser\Node         $node
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return string[] errors
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		if (!isset($node->namespacedName)) {
			// anonymous class
			return [];
		}

		$className = (string) $node->namespacedName;

		$reflection = new \ReflectionClass($className);

		if (!$reflection->isSubclassOf(Enum::class)) {
			return [];
		}
		if ($reflection->isFinal() || $reflection->isAbstract()) {
			return [];
		}

		return [
			sprintf(
				"Class %s extends %s, but it's usage is not safe - explicitly mark the class as final or abstract",
				$className,
				Enum::class
			),
		];
	}

}
