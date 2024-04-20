<?php declare(strict_types = 1);

namespace MangoShopTests\Channel\Cases\Integration\Api;

use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\Payment\Bridges\NetteDI\PaymentBootstrapExtension;
use MangoShop\PaymentGoPay\Api\GoPayPaymentFacade;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nette;
use Nette\Configurator;
use Nette\DI\Container;
use Psr\Log\LoggerInterface;
use Tester\Assert;
use Tester\Helpers;

$containerFactory = require __DIR__ . '/../../../../bootstrap.php';


/**
 * @testCase
 */
class PaymentBootstrapExtensionTest extends TestCase
{
	public function testExtension(Container $appContainer)
	{
		$tempDir = $appContainer->parameters['tempDir'] . '/PaymentBootstrapExtensionTest';
		Helpers::purge($tempDir);

		$configurator = new Configurator();
		$configurator->defaultExtensions = [
			'mango.shop' => PaymentBootstrapExtension::class,
		];

		$configurator->addParameters([
			'tempDir' => $tempDir,
		]);

		$configurator->addConfig([
			'mango.shop' => [
				'tempDir' => $tempDir,
				'baseUrl' => 'https://example.com',
				'dbal' => [
					'host' => '127.0.0.1',
					'port' => '1234',
					'username' => 'john.smith',
					'password' => 'lorem ipsum',
					'database' => 'nette_shop_db_name',
				],
				'gopay' => [
					'goid' => 123456789,
					'clientId' => 987654321,
					'clientSecret' => 'ABC123',
					'isProductionMode' => false,
					'paymentMethodCode' => 'xgopay',
					'returnEndpointUrl' => 'https://example.com/return',
					'notifyEndpointUrl' => 'https://example.com/notify',
				],
			],
			'services' => [
				'logger' => [
					'type' => LoggerInterface::class,
					'factory' => new Nette\DI\Statement('Mockery::mock', [LoggerInterface::class]),
				],
			],
		]);

		$outerContainer = $configurator->createContainer();
		Assert::false($outerContainer->hasService('mango.shop.orderFacade'));
		Assert::false($outerContainer->hasService('mango.shop.productFacade'));
		Assert::type(PaymentFacade::class, $outerContainer->getByType(PaymentFacade::class));
		Assert::type(GoPayPaymentFacade::class, $outerContainer->getByType(GoPayPaymentFacade::class));
	}
}


PaymentBootstrapExtensionTest::run($containerFactory);
