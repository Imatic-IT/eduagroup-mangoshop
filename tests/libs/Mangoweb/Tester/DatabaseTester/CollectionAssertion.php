<?php declare(strict_types = 1);

namespace Mangoweb\Tester\DatabaseTester;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Relationships\HasMany;


class CollectionAssertion implements IAssertion
{
	public static $defaultSorting = NULL;

	/** @var array */
	private $expected;

	/** @var array|NULL */
	private $sortBy;


	public function __construct(array $expected, array $sortBy = NULL)
	{
		$this->expected = $expected;
		$this->sortBy = $sortBy;
	}


	public static function sortedCollection(array $sortBy, array $expected)
	{
		return new self($expected, $sortBy);
	}


	public function assert($actual, AssertionContext $context): void
	{
		if ($actual instanceof HasMany) {
			$actual = $actual->get();
		}
		$sorting = $this->sortBy ?? self::$defaultSorting ?? NULL;
		if ($sorting !== NULL) {
			$actual = $actual->orderBy(...$sorting);
		}
		$context->assertType(ICollection::class, $actual);

		$context->assertCount(count($this->expected), $actual);
		reset($this->expected);
		foreach ($actual as $i => $subEntity) {
			$expected = current($this->expected);
			if ($expected === FALSE) {
				return;
			}
			$context->assert($expected, $subEntity, (string) $i);
			next($this->expected);
		}
	}

}
