<?php declare(strict_types = 1);

namespace MangoShopTests\Bicistickers\Cases\Integration\Model;

use MangoShop;
use MangoShop\Bicistickers\Model\BicistickersOrderProcessing;
use MangoShop\Bicistickers\Model\BicistickersOrderProcessingStateEnum;
use MangoShop\Bicistickers\Model\OrderPrintedTransition;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Order\Api\OrderFacade;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderStateEnum;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\PaymentService;
use MangoShopTests\Bicistickers\Inc\BicistickersHook;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Order\Inc\OrderEntityFactory;
use MangoShopTests\Payment\Inc\DummyExternalStateCodeEnum;
use Mangoweb\MailTester\MailTester;
use Mangoweb\Tester\Infrastructure\Container\IAppContainerHook;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nette\Configurator;
use Nette\DI\Container;
use Nextras\Orm\Model\IModel;

$configurator = require __DIR__ . '/../../../../../bootstrap-configurator.php';


/**
 * @testCase
 */
class OrderMailingTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;

	/** @var MailTester */
	private $mailTester;


	public function __construct(EntityGenerator $entityGenerator, MailTester $mailTester)
	{
		$this->entityGenerator = $entityGenerator;
		$this->mailTester = $mailTester;
	}


	public function testMailAfterPayment(IModel $orm, TransactionManager $transactionManager, PaymentService $paymentService)
	{
		$order = $this->entityGenerator->createInOrm($orm, MangoShop\Order\Model\Order::class, ['cart' => OrderEntityFactory::exampleCart()]);
		$transaction = $transactionManager->begin();
		$paymentService->advanceByExternalState($transaction, $order->payment, new ExternalState(DummyExternalStateCodeEnum::APPROVED(), []));
		$transactionManager->flush($transaction);

		$this->mailTester->consumeSingle()
			->assertRecipient('frantisek@dobrota.cz')
			->assertBody('body')
			->assertSubject('Order #1 created');
	}


	public function testDispatchMail(OrderFacade $orderFacade)
	{
		$order = $this->entityGenerator->create(Order::class, [
			'cart' => OrderEntityFactory::exampleCart(),
			'state' => OrderStateEnum::PROCESSING(),
			'processing' => [
				'callback' => function (array $data) {
					return new BicistickersOrderProcessing($data['order'], BicistickersOrderProcessingStateEnum::WAITING_TO_PRINT());
				},
			]
		]);

		$this->mailTester->assertNone();

		$orderFacade->advanceProcessing($order->id, new OrderPrintedTransition());

		$this->mailTester->consumeSingle()
			->assertRecipient('frantisek@dobrota.cz')
			->assertBody('body')
			->assertSubject('Order #1 dispatched');
	}


	protected static function getContainerHook(Container $testContainer): ?IAppContainerHook
	{
		return new BicistickersHook();
	}
}


assert($configurator instanceof Configurator);
$configurator->addConfig([
	'app.infrastructure' => [
		'orderProcessingEntity' => BicistickersOrderProcessing::class,
	],
]);

OrderMailingTest::run([$configurator, 'createContainer']);
