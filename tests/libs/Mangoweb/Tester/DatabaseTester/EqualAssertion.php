<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;


class EqualAssertion implements IAssertion
{

	/** @var mixed */
	private $expected;


	public function __construct($expected)
	{
		$this->expected = $expected;
	}


	public function assert($actual, AssertionContext $context): void
	{
		$context->assertEqual($this->expected, $actual);
	}

}
