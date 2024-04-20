<?php declare(strict_types = 1);

namespace MangoShopTests\Channel\Cases\Integration\Api;

use MangoShop\Channel\Bridges\NetteDI\OrderBootstrapExtension;
use MangoShop\Order\Api\OrderFacade;
use MangoShop\Order\Model\IOrderProcessingDriver;
use MangoShop\Order\Model\OrderProcessing;
use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\PaymentGoPay\Api\GoPayPaymentFacade;
use MangoShop\Product\Api\ProductFacade;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mockery;
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
class OrderBootstrapExtensionTest extends TestCase
{
	public function testExtension(Container $appContainer)
	{
		$tempDir = $appContainer->parameters['tempDir'] . '/OrderBootstrapExtensionTest';
		Helpers::purge($tempDir);

		$configurator = new Configurator();
		$configurator->defaultExtensions = [
			'mango.shop' => OrderBootstrapExtension::class,
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
				'order' => [
					'processingEntity' => get_class(Mockery::mock(OrderProcessing::class)),
				],
			],
			'services' => [
				'logger' => [
					'type' => LoggerInterface::class,
					'factory' => new Nette\DI\Statement('Mockery::mock', [LoggerInterface::class]),
				],
				'orderProcessingDriver' => [
					'type' => IOrderProcessingDriver::class,
					'factory' => new Nette\DI\Statement('Mockery::mock', [IOrderProcessingDriver::class]),
				],
			],
		]);

		$outerContainer = $configurator->createContainer();
		Assert::type(OrderFacade::class, $outerContainer->getByType(OrderFacade::class));
		Assert::type(ProductFacade::class, $outerContainer->getByType(ProductFacade::class));
		Assert::type(PaymentFacade::class, $outerContainer->getByType(PaymentFacade::class));
		Assert::type(GoPayPaymentFacade::class, $outerContainer->getByType(GoPayPaymentFacade::class));
	}
}


OrderBootstrapExtensionTest::run($containerFactory);
