<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;

use Nextras\Orm\Entity\IEntity;


class EntityAssertion implements IAssertion
{

	/** @var IEntity|array|NULL */
	private $expected;


	public function __construct($expected)
	{
		$this->expected = $expected;
	}


	public function assert($actual, AssertionContext $context): void
	{
		$expected = $this->expected;
		if ($expected === NULL) {
			$context->assertNull($actual);
			return;
		}

		$context->assertType(IEntity::class, $actual);
		assert($actual instanceof IEntity);
		if ($expected instanceof IEntity) {
			$context->assertType(get_class($expected), $actual);
			$context->assertSame($expected->getPersistedId(), $actual->getPersistedId());
			return;
		}
		assert(is_array($expected));
		foreach ($expected as $field => $expectedOne) {
			if (is_int($field)) {
				$actualValue = $actual;
			} else {
				$actualValue = $actual->getValue($field);
			}
			$context->assert($expectedOne, $actualValue, is_int($field) ? NULL : $field);
		}
	}

}
