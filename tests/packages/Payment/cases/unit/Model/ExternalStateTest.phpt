<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Unit\Model;

use MangoShop\Payment\Model\ExternalState;
use MangoShopTests\Payment\Inc\DummyExternalStateCodeEnum;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class ExternalStateTest extends TestCase
{
	/**
	 * @dataProvider provideEqualsData
	 */
	public function testEquals(ExternalState $a, ?ExternalState $b, bool $expectedResult)
	{
		Assert::same($expectedResult, $a->equals($b));
	}


	public function provideEqualsData(): iterable
	{
		yield [
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), []),
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), []),
			true
		];

		yield [
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), []),
			new ExternalState(DummyExternalStateCodeEnum::APPROVED(), []),
			false
		];

		yield [
			new ExternalState(DummyExternalStateCodeEnum::APPROVED(), ['a' => 1]),
			new ExternalState(DummyExternalStateCodeEnum::APPROVED(), ['a' => 2]),
			false
		];

		yield [
			new ExternalState(DummyExternalStateCodeEnum::APPROVED(), []),
			null,
			false
		];
	}
}


ExternalStateTest::run($containerFactory);
