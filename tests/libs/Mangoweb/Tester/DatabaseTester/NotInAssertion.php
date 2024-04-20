<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;


class NotInAssertion implements IAssertion
{

	/** @var iterable */
	private $notExpected;


	public function __construct(iterable $notExpected)
	{
		$this->notExpected = $notExpected;
	}


	public function assert($actual, AssertionContext $context): void
	{
		foreach ($this->notExpected as $notExpectedSingle) {
			$assertion = $context->detectAssertion($notExpectedSingle, $actual);
			$localResult = new AssertionContext();
			$assertion->assert($actual, $localResult);
			if (!$localResult->getErrors()) {
				$context->addError('%1 should not be %2', $notExpectedSingle, $actual);
			}
		}
	}

}
