<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;


interface IAssertion
{
	/**
	 * @param mixed            $actual
	 * @param AssertionContext $context
	 */
	public function assert($actual, AssertionContext $context): void;

}
