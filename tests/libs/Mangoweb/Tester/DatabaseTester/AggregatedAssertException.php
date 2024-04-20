<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;

use Tester\AssertException;


class AggregatedAssertException extends AssertException
{

	/** @var AssertException[] */
	private $exceptions;


	/**
	 * @param mixed             $expected
	 * @param mixed             $actual
	 * @param AssertException[] $exceptions
	 */
	public function __construct($expected, $actual, array $exceptions)
	{
		assert(count($exceptions) > 0);
		$message = "%1 does not match %2: \n";
		$message .= count($exceptions) . " failures:\n";
		foreach ($exceptions as $exception) {
			assert($exception instanceof AssertException);
			$message .= $exception->getMessage() . "\n";
		}
		$this->exceptions = $exceptions;
		parent::__construct(substr($message, 0, -1), $expected, $actual);
	}


	public function getExceptions(): array
	{
		return $this->exceptions;
	}

}
