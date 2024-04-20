<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;

use Nette\StaticClass;
use Nextras\Orm\Entity\IEntity;
use Tester\Assert;


class EntityAssert
{
	use StaticClass;

	public static function assert($expected, $actual): void
	{
		Assert::$counter++;
		$context = new AssertionContext();
		if ($actual && $actual instanceof IEntity) {
			$entityName = get_class($actual) . '#' . ltrim((string) $actual->getPersistedId(), '0-');
		} else {
			$entityName = '$entity';
		}
		$context->assert($expected, $actual, $entityName);
		$context->raiseError($expected, $actual);
	}

}
