<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Unit\Model;

use MangoShop\Payment\Model\FailureReasonEnum;
use MangoShop\Payment\Model\InternalState;
use MangoShop\Payment\Model\InternalStateCodeEnum;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class InternalStateTest extends TestCase
{
	/**
	 * @dataProvider provideEqualsData
	 */
	public function testEquals(InternalState $a, InternalState $b, bool $expectedResult)
	{
		Assert::same($expectedResult, $a->equals($b));
	}


	public function provideEqualsData(): iterable
	{
		yield [
			new InternalState(InternalStateCodeEnum::CREATED(), null),
			new InternalState(InternalStateCodeEnum::CREATED(), null),
			true
		];

		yield [
			new InternalState(InternalStateCodeEnum::APPROVED(), null),
			new InternalState(InternalStateCodeEnum::APPROVED(), null),
			true
		];

		yield [
			new InternalState(InternalStateCodeEnum::CREATED(), null),
			new InternalState(InternalStateCodeEnum::APPROVED(), null),
			false
		];

		yield [
			new InternalState(InternalStateCodeEnum::FAILED(), FailureReasonEnum::DENIED()),
			new InternalState(InternalStateCodeEnum::FAILED(), FailureReasonEnum::DENIED()),
			true
		];

		yield [
			new InternalState(InternalStateCodeEnum::FAILED(), FailureReasonEnum::DENIED()),
			new InternalState(InternalStateCodeEnum::FAILED(), FailureReasonEnum::CANCELED()),
			false
		];
	}
}


InternalStateTest::run($containerFactory);
