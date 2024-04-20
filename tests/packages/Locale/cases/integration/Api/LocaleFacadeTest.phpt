<?php declare(strict_types = 1);

namespace MangoShopTests\Locale\Cases\Integration\Api;

use MangoShop;
use MangoShop\Locale\Api\LocaleFacade;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class LocaleFacadeTest extends TestCase
{
	public function testFlow(LocaleFacade $localeFacade)
	{
		$csLocale = $localeFacade->create('cs');
		$enLocale = $localeFacade->create('en');

		Assert::same($csLocale, $localeFacade->getById(1));
		Assert::same($enLocale, $localeFacade->getById(2));

		Assert::same($csLocale, $localeFacade->getByCode('cs'));
		Assert::same($enLocale, $localeFacade->getByCode('en'));

		Assert::exception(
			function () use ($localeFacade) {
				$localeFacade->getById(123);
			},
			MangoShop\Core\Api\EntityNotFoundException::class,
			'Required entity MangoShop\Locale\Model\Locale with id 123 was not found'
		);

		Assert::exception(
			function () use ($localeFacade) {
				$localeFacade->getByCode('xx');
			},
			MangoShop\Core\Api\EntityNotFoundException::class,
			'Required entity MangoShop\Locale\Model\Locale was not found'
		);
	}
}


LocaleFacadeTest::run($containerFactory);
