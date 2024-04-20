<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;

use DateTimeInterface;


class DateTimeAssertion implements IAssertion
{

	/** @var DateTimeInterface */
	private $expected;


	public function __construct(DateTimeInterface $expected)
	{
		$this->expected = $expected;
	}


	public function assert($actual, AssertionContext $context): void
	{
		if (!$context->assertNotSame(NULL, $actual)) {
			return;
		}
		assert($actual instanceof DateTimeInterface);
		$context->assertSame($this->expected->getTimestamp(), $actual->getTimestamp());
	}

}
