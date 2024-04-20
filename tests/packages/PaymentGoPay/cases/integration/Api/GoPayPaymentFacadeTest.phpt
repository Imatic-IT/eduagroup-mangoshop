<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Integration\Api;

use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\InvalidPaymentExternalStateTransitionException;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentException;
use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\PaymentGoPay\Api\GoPayPaymentFacade;
use MangoShop\PaymentGoPay\Model\GoPayPaymentMethod;
use MangoShop\PaymentGoPay\Model\GoPayStateCodeEnum;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Payment\Inc\PaymentEntityFactory;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mangoweb\Tester\LogTester\LogTester;
use Nextras\Orm\Model\IModel;
use Psr\Log\LogLevel;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class GoPayPaymentFacadeTest extends TestCase
{
	private const PAID_EXTERNAL_IDENTIFIER = '3055616447';
	private const REFUNDED_EXTERNAL_IDENTIFIER = '3055615576';
	private const CANCELED_EXTERNAL_IDENTIFIER = '3055623558';
	private const TIMEOUTED_EXTERNAL_IDENTIFIER = '3055601354';

	/** @var EntityGenerator */
	private $entityGenerator;

	/** @var LogTester */
	private $logTester;


	public function __construct(EntityGenerator $entityGenerator, LogTester $logTester)
	{
		$this->entityGenerator = $entityGenerator;
		$this->logTester = $logTester;
	}


	public function testGetByExternalIdentifier(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
		]));

		$fetchedPayment = $facade->getByExternalIdentifier($payment->externalIdentifier);
		Assert::type(Payment::class, $fetchedPayment);
	}


	public function testProcessGatewayReturnWithCreatedState(GoPayPaymentFacade $facade, PaymentFacade $paymentFacade)
	{
		$payment = $this->entityGenerator->create(Payment::class, [
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
		]);

		$response = $paymentFacade->initialize($payment->id, new PaymentInitializationRequest(null, null, []));
		$returnedPayment = $facade->processGatewayReturn($response->getExternalIdentifier());
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::CREATED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => null], $returnedPayment->state->externalState->getData());

		$this->logTester->consumeOne(LogLevel::INFO, 'GoPayPaymentFacade: refreshState() did not result in change of external state', [
			'paymentId' => $returnedPayment->id,
			'externalIdentifier' => $returnedPayment->externalIdentifier,
			'externalState' => [
				'code' => 'CREATED',
				'data' => [
					'subState' => null,
				],
			],
		]);
	}


	public function testProcessGatewayReturnWithPaidState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::PAID_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CREATED(), []),
		]));

		$returnedPayment = $facade->processGatewayReturn($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::PAID(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => null], $returnedPayment->state->externalState->getData());
	}


	public function testProcessGatewayReturnWithCanceledState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::CANCELED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CREATED(), []),
		]));

		$returnedPayment = $facade->processGatewayReturn($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::CANCELED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => '_5036'], $returnedPayment->state->externalState->getData());
	}


	public function testProcessGatewayReturnWithCanceledStateFromCanceled(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::CANCELED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CANCELED(), ['subState' => '_5036']),
		]));

		$returnedPayment = $facade->processGatewayReturn($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::CANCELED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => '_5036'], $returnedPayment->state->externalState->getData());

		$this->logTester->consumeOne(LogLevel::INFO, 'GoPayPaymentFacade: refreshState() did not result in change of external state', [
			'paymentId' => $returnedPayment->id,
			'externalIdentifier' => $returnedPayment->externalIdentifier,
			'externalState' => [
				'code' => 'CANCELED',
				'data' => ['subState' => '_5036'],
			],
		]);
	}


	public function testProcessGatewayReturnWithCanceledStateFromPaidState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::CANCELED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::PAID(), []),
		]));

		$exception = Assert::exception(
			function () use ($facade, $payment) {
				$facade->processGatewayReturn($payment->externalIdentifier);
			},
			InvalidPaymentExternalStateTransitionException::class,
			'Invalid transition from external state \'PAID\' to external state \'CANCELED\'. Allowed transitions are \'REFUNDED\', \'PARTIALLY_REFUNDED\'.'
		);

		Assert::type(PaymentException::class, $exception);
		Assert::same($payment->id, $exception->getPayment()->id);
		Assert::same(GoPayStateCodeEnum::CANCELED(), $exception->getNewExternalState()->getCode());
		Assert::same(['subState' => '_5036'], $exception->getNewExternalState()->getData());
		Assert::same(['REFUNDED', 'PARTIALLY_REFUNDED'], $exception->getAllowedNextExternalStateCodes());
	}


	public function testProcessGatewayReturnWithTimeoutedState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::TIMEOUTED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CREATED(), []),
		]));

		$returnedPayment = $facade->processGatewayReturn($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::TIMEOUTED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => null], $returnedPayment->state->externalState->getData());
	}


	public function testProcessGatewayNotificationWithPaidState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::PAID_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CREATED(), []),
		]));

		$returnedPayment = $facade->processGatewayNotification($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::PAID(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => null], $returnedPayment->state->externalState->getData());
	}


	public function testProcessGatewayNotificationWithCanceledState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::CANCELED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CREATED(), []),
		]));

		$returnedPayment = $facade->processGatewayNotification($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::CANCELED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => '_5036'], $returnedPayment->state->externalState->getData());
	}


	public function testProcessGatewayNotificationWithTimeoutedState(GoPayPaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::TIMEOUTED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::CREATED(), []),
		]));

		$returnedPayment = $facade->processGatewayNotification($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::TIMEOUTED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => null], $returnedPayment->state->externalState->getData());
	}


	public function testProcessGatewayNotificationWithRefundedState(GoPayPaymentFacade $facade, IModel $orm)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createApprovedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => self::REFUNDED_EXTERNAL_IDENTIFIER,
			'externalState' => new ExternalState(GoPayStateCodeEnum::PAID(), []),
		]));

		$returnedPayment = $facade->processGatewayNotification($payment->externalIdentifier);
		Assert::same($payment->id, $returnedPayment->id);
		Assert::same(GoPayStateCodeEnum::REFUNDED(), $returnedPayment->state->externalState->getCode());
		Assert::same(['subState' => null], $returnedPayment->state->externalState->getData());
	}
}


GoPayPaymentFacadeTest::run($containerFactory);
