<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Cases\Integration\Api;

use MangoShop;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Order\Model\OrderFailureReasonEnum;
use MangoShop\Order\Model\OrderStateEnum;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\PaymentService;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Order\Inc\OrderEntityFactory;
use MangoShopTests\Order\Inc\TestOrderProcessing;
use MangoShopTests\Payment\Inc\DummyExternalStateCodeEnum;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nextras\Orm\Model\IModel;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class OrderPaymentTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testApprovePayment(IModel $orm, TransactionManager $transactionManager, PaymentService $paymentService)
	{
		$order = $this->entityGenerator->createInOrm($orm, MangoShop\Order\Model\Order::class, ['cart' => OrderEntityFactory::exampleCart()]);
		$transaction = $transactionManager->begin();
		$paymentService->advanceByExternalState($transaction, $order->payment, new ExternalState(DummyExternalStateCodeEnum::APPROVED(), []));
		$transactionManager->flush($transaction);

		Assert::same(OrderStateEnum::PROCESSING(), $order->state);
		Assert::type(TestOrderProcessing::class, $order->processing);
	}


	public function testFailedPayment(IModel $orm, TransactionManager $transactionManager, PaymentService $paymentService)
	{
		$order = $this->entityGenerator->createInOrm($orm, MangoShop\Order\Model\Order::class, ['cart' => OrderEntityFactory::exampleCart()]);
		$transaction = $transactionManager->begin();
		$paymentService->advanceByExternalState($transaction, $order->payment, new ExternalState(DummyExternalStateCodeEnum::FAILED(), [
			'failureReason' => MangoShop\Payment\Model\FailureReasonEnum::DENIED,
		]));
		$transactionManager->flush($transaction);

		Assert::same(OrderStateEnum::FAILED(), $order->state);
		Assert::same(OrderFailureReasonEnum::PAYMENT_FAILED(), $order->failureReason);

	}
}


OrderPaymentTest::run($containerFactory);
