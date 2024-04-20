<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;


class MatchAssertion implements IAssertion
{

	/** @var string */
	private $pattern;


	public function __construct(string $pattern)
	{
		$this->pattern = $pattern;
	}


	public function assert($actual, AssertionContext $context): void
	{
		$context->assertMatch($this->pattern, $actual);
	}

}
