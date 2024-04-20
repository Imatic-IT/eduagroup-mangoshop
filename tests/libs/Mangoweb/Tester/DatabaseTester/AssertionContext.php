<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;

use DateTimeInterface;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Relationships\HasMany;
use Tester\Assert;
use Tester\AssertException;


/**
 * @method assertSame($expected, $actual, $description = NULL)
 * @method assertNotSame($expected, $actual, $description = NULL)
 * @method assertEqual($expected, $actual, $description = NULL)
 * @method assertNotEqual($expected, $actual, $description = NULL)
 * @method assertContains($needle, $actual, $description = NULL)
 * @method assertNotContains($needle, $actual, $description = NULL)
 * @method assertTrue($actual, $description = NULL)
 * @method assertFalse($actual, $description = NULL)
 * @method assertNull($actual, $description = NULL)
 * @method assertNan($actual, $description = NULL)
 * @method assertTruthy($actual, $description = NULL)
 * @method assertFalsey($actual, $description = NULL)
 * @method assertCount($count, $value, $description = NULL)
 * @method assertType($type, $value, $description = NULL)
 * @method assertException(callable $function, $class, $message = NULL, $code = NULL)
 * @method assertError(callable $function, $expectedType, $expectedMessage = NULL)
 * @method assertNoError($function)
 * @method assertMatch($pattern, $actual, $description = NULL)
 * @method assertMatchFile($file, $actual, $description = NULL)
 */
class AssertionContext
{

	/** @var AssertException[] */
	private $errors = [];

	/** @var array */
	private $path = [];


	public function detectAssertion($expected, $actual): IAssertion
	{
		if ($expected instanceof IAssertion) {
			return $expected;
		}
		if ($actual instanceof HasMany || $actual instanceof ICollection) {
			if (is_int($expected)) {
				$expected = array_fill(0, $expected, []);
			}
			return new CollectionAssertion($expected);
		}
		if ($actual instanceof IEntity) {
			return new EntityAssertion($expected);
		}
		if ($expected instanceof DateTimeInterface) {
			return new DateTimeAssertion($expected);
		}
		return new SameAssertion($expected);
	}


	public function assert($expected, $actual, string $path = NULL): void
	{
		if ($path !== NULL) {
			$this->path[] = $path;
		}
		$this->detectAssertion($expected, $actual)->assert($actual, $this);
		array_pop($this->path);
	}


	public function __call($name, $arguments)
	{
		if (substr($name, 0, 6) !== 'assert') {
			throw new \LogicException("Undefined method $name");
		}

		$name = substr($name, 6);
		try {
			Assert::$name(...$arguments);
			return TRUE;
		} catch (AssertException $e) {
			$this->addException($e);
			return FALSE;
		}
	}


	public function addError(string $message, $expected, $actual): void
	{
		$this->addException(new AssertException($message, $expected, $actual));
	}


	private function addException(AssertException $exception): void
	{
		if ($this->path) {
			$pathPrefix = implode('->', $this->path) . ': ';
		} else {
			$pathPrefix = '';
		}
		$exception->setMessage($pathPrefix . $exception->origMessage);
		$this->errors[] = $exception;
	}


	public function getErrors(): array
	{
		return $this->errors;
	}


	public function raiseError($expected, $actual): void
	{
		if (count($this->errors) === 1) {
			throw reset($this->errors);
		}
		if (count($this->errors) > 0) {
			throw new AggregatedAssertException($expected, $actual, $this->errors);
		}
	}

}
