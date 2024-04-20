<?php declare(strict_types = 1);

namespace MangoShopTests\Money\Cases\Integration\Api;

use MangoShop;
use MangoShop\Money\Api\CurrencyFacade;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class CurrencyFacadeTest extends TestCase
{
	public function testFlow(CurrencyFacade $currencyFacade)
	{
		$czkCurrency = $currencyFacade->create('CZK');
		$usdCurrency = $currencyFacade->create('USD');

		Assert::same($czkCurrency, $currencyFacade->getById(1));
		Assert::same($usdCurrency, $currencyFacade->getById(2));

		Assert::same($czkCurrency, $currencyFacade->getByCode('CZK'));
		Assert::same($usdCurrency, $currencyFacade->getByCode('USD'));

		Assert::exception(
			function () use ($currencyFacade) {
				$currencyFacade->getById(123);
			},
			MangoShop\Core\Api\EntityNotFoundException::class,
			'Required entity MangoShop\Money\Model\Currency with id 123 was not found'
		);

		Assert::exception(
			function () use ($currencyFacade) {
				$currencyFacade->getByCode('XXX');
			},
			MangoShop\Core\Api\EntityNotFoundException::class,
			'Required entity MangoShop\Money\Model\Currency was not found'
		);
	}
}


CurrencyFacadeTest::run($containerFactory);
