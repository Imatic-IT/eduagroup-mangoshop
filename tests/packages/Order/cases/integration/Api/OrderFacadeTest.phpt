<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Cases\Integration\Api;

use MangoShop;
use MangoShop\Order\Api\OrderFacade;
use MangoShop\Order\Model\Cart;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderFailureReasonEnum;
use MangoShop\Order\Model\OrderStateEnum;
use MangoShopTests\EntityGenerator;
use MangoShopTests\EntityReference;
use MangoShopTests\Order\Inc\OrderEntityFactory;
use MangoShopTests\Order\Inc\TestOrderProcessingStateEnum;
use MangoShopTests\Order\Inc\TestOrderProcessingTransition;
use Mangoweb\Clock\Clock;
use Mangoweb\MailTester\MailTester;
use Mangoweb\Tester\DatabaseTester\EntityAssert;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nextras\Orm\Model\IModel;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


class OrderFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testCreateOrder(IModel $orm, OrderFacade $orderFacade)
	{
		$cart = $this->entityGenerator->createInOrm($orm, Cart::class, OrderEntityFactory::exampleCart([
			'billingInfo' => [
				'address' => [
					'country' => $countryRef = new EntityReference(),
				],
			],
			'productItems' => [
				[
					'variant' => $variant1Ref = new EntityReference(),
				],
				[
					'variant' => $variant2Ref = new EntityReference(),
				],
			],
			'promotionItems' => 2,
		]));

		$order = $orderFacade->create($cart);
		Assert::same(24000, $order->payment->amountCents); // 30000 - (0.1 + 0.1 * 30000)

		EntityAssert::assert([
			'context' => $cart->context,
			'billingInfo' => [
				'address' => [
					'recipientName' => 'Frantisek Dobrota',
					'country' => $countryRef->getEntity(),
				],
			],
			'shippingInfo' => [
				'address' => [
					'recipientName' => 'Frantisek Zlota',
					'country' => $countryRef->getEntity(),
				],
			],
			'productItems' => [
				[
					'productVariant' => $variant1Ref->getEntity(),
					'quantity' => 1,
				],
				[
					'productVariant' => $variant2Ref->getEntity(),
					'quantity' => 2,
				]
			]
		], $order);
	}


	public function testInitializePayment(OrderFacade $orderFacade)
	{
		$order = $this->entityGenerator->create(Order::class, [
			'cart' => OrderEntityFactory::exampleCart([
				'paymentMethod' => [
					'code' => '__dummy',
				],
				'promotionItems' => 2,
			]),
		]);

		$response = $orderFacade->initializePayment($order->id);
		Assert::same('https://dummy-gateway.url/', $response->getGatewayUrl());
		Assert::same('dummy#1', $response->getExternalIdentifier());
	}


	public function testCancel(OrderFacade $orderFacade)
	{
		$orderId = $this->entityGenerator->create(Order::class, ['cart' => OrderEntityFactory::exampleCart()])->id;
		$order = $orderFacade->cancelByShop($orderId);

		Assert::same(OrderStateEnum::FAILED(), $order->state);
		Assert::same(OrderFailureReasonEnum::CANCEL_SHOP(), $order->failureReason);
	}


	public function testProcess(OrderFacade $orderFacade)
	{
		$order = $this->entityGenerator->create(Order::class, [
			'cart' => OrderEntityFactory::exampleCart(),
			'state' => OrderStateEnum::PROCESSING(),
		]);

		EntityAssert::assert([
			'state' => OrderStateEnum::PROCESSING(),
			'processingStartedAt' => Clock::now(),
			'processing' => [
				'state' => TestOrderProcessingStateEnum::CREATED(),
			],
		], $order);

		$order = $orderFacade->advanceProcessing($order->id, new TestOrderProcessingTransition(TestOrderProcessingStateEnum::PACKING()));

		EntityAssert::assert([
			'state' => OrderStateEnum::PROCESSING(),
			'processing' => [
				'state' => TestOrderProcessingStateEnum::PACKING(),
			],
		], $order);

		$order = $orderFacade->advanceProcessing($order->id, new TestOrderProcessingTransition(TestOrderProcessingStateEnum::DISPATCHED()));

		EntityAssert::assert([
			'state' => OrderStateEnum::DISPATCHED(),
			'dispatchedAt' => Clock::now(),
			'processing' => [
				'state' => TestOrderProcessingStateEnum::DISPATCHED(),
			],
		], $order);
	}
}


OrderFacadeTest::run($containerFactory);
