<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Integration\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\FailureReasonEnum;
use MangoShop\Payment\Model\InternalStateCodeEnum;
use MangoShop\Payment\Model\InvalidPaymentInternalStateTransitionException;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentException;
use MangoShop\Payment\Model\PaymentService;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Payment\Inc\DummyExternalStateCodeEnum;
use MangoShopTests\Payment\Inc\PaymentEntityFactory;
use Mangoweb\Tester\Infrastructure\TestCase;
use Mockery;
use Nextras\Orm\Model\IModel;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class PaymentServiceTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testInitializeExternalStateOk(PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->create(Payment::class);

		$transaction->shouldReceive('persist')->withArgs([$payment]);

		$paymentService->initializeExternalState(
			$transaction,
			$payment,
			'ABC123',
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), ['xyz' => 123])
		);

		Assert::same('ABC123', $payment->externalIdentifier);
		Assert::same(InternalStateCodeEnum::CREATED(), $payment->state->internalStateCode);
		Assert::null($payment->state->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->externalState->getCode());
		Assert::same(['xyz' => 123], $payment->state->externalStateData);

		Assert::null($payment->state->previousVersion->externalState);
		Assert::same([], $payment->state->previousVersion->externalStateData);
	}


	public function testInitializeExternalStateTwiceFailure(PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment());

		Assert::exception(
			function () use ($paymentService, $transaction, $payment) {
				$paymentService->initializeExternalState(
					$transaction,
					$payment,
					'ABC123',
					new ExternalState(DummyExternalStateCodeEnum::CREATED(), ['xyz' => 123])
				);
			},
			\AssertionError::class
		);
	}


	public function testAdvanceByExternalStateToSameInternalState(PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment());

		$transaction->shouldReceive('persist')->times(3)->withArgs([$payment]);

		$paymentService->advanceByExternalState(
			$transaction,
			$payment,
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), ['foo' => 1])
		);

		$paymentService->advanceByExternalState(
			$transaction,
			$payment,
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), ['foo' => 2])
		);

		$paymentService->advanceByExternalState(
			$transaction,
			$payment,
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), ['foo' => 3])
		);

		Assert::same(InternalStateCodeEnum::CREATED(), $payment->state->internalStateCode);
		Assert::null($payment->state->internalStateFailureReason);

		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->externalState->getCode());
		Assert::same(['foo' => 3], $payment->state->externalStateData);

		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->previousVersion->externalState->getCode());
		Assert::same(['foo' => 2], $payment->state->previousVersion->externalStateData);

		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->previousVersion->previousVersion->externalState->getCode());
		Assert::same(['foo' => 1], $payment->state->previousVersion->previousVersion->externalStateData);

		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->previousVersion->previousVersion->previousVersion->externalState->getCode());
		Assert::same([], $payment->state->previousVersion->previousVersion->previousVersion->externalStateData);
	}


	public function testAdvanceByExternalStateToApprovedState(PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment());

		$transaction->shouldReceive('persist')->times(1)->withArgs([$payment]);

		$paymentService->advanceByExternalState(
			$transaction,
			$payment,
			new ExternalState(DummyExternalStateCodeEnum::APPROVED(), [])
		);

		Assert::same(InternalStateCodeEnum::APPROVED(), $payment->state->internalStateCode);
		Assert::null($payment->state->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::APPROVED(), $payment->state->externalState->getCode());
		Assert::same([], $payment->state->externalStateData);
		Assert::type(DateTimeImmutable::class, $payment->approvedAt);

		Assert::same(InternalStateCodeEnum::CREATED(), $payment->state->previousVersion->internalStateCode);
		Assert::null($payment->state->previousVersion->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->previousVersion->externalState->getCode());
		Assert::same([], $payment->state->previousVersion->externalStateData);
	}


	public function testAdvanceByExternalStateToFailedStateFromCreatedState(PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment());

		$transaction->shouldReceive('persist')->times(1)->withArgs([$payment]);

		$paymentService->advanceByExternalState(
			$transaction,
			$payment,
			new ExternalState(DummyExternalStateCodeEnum::FAILED(), ['failureReason' => FailureReasonEnum::TIMEOUTED()->getValue()])
		);

		Assert::same(InternalStateCodeEnum::FAILED(), $payment->state->internalStateCode);
		Assert::same(FailureReasonEnum::TIMEOUTED(), $payment->state->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::FAILED(), $payment->state->externalState->getCode());
		Assert::same(['failureReason' => 'timeouted'], $payment->state->externalStateData);
		Assert::type(DateTimeImmutable::class, $payment->failedAt);

		Assert::same(InternalStateCodeEnum::CREATED(), $payment->state->previousVersion->internalStateCode);
		Assert::null($payment->state->previousVersion->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->previousVersion->externalState->getCode());
		Assert::same([], $payment->state->previousVersion->externalStateData);
	}


	public function testAdvanceByExternalStateToFailedStateFromApprovedState(PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createApprovedPayment());

		$transaction->shouldReceive('persist')->times(1)->withArgs([$payment]);

		$paymentService->advanceByExternalState(
			$transaction,
			$payment,
			new ExternalState(DummyExternalStateCodeEnum::FAILED(), ['failureReason' => FailureReasonEnum::TIMEOUTED()->getValue()])
		);

		Assert::same(InternalStateCodeEnum::FAILED(), $payment->state->internalStateCode);
		Assert::same(FailureReasonEnum::TIMEOUTED(), $payment->state->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::FAILED(), $payment->state->externalState->getCode());
		Assert::same(['failureReason' => 'timeouted'], $payment->state->externalStateData);
		Assert::type(DateTimeImmutable::class, $payment->approvedAt);
		Assert::type(DateTimeImmutable::class, $payment->failedAt);

		Assert::same(InternalStateCodeEnum::APPROVED(), $payment->state->previousVersion->internalStateCode);
		Assert::null($payment->state->previousVersion->internalStateFailureReason);
		Assert::same(DummyExternalStateCodeEnum::APPROVED(), $payment->state->previousVersion->externalState->getCode());
		Assert::same([], $payment->state->previousVersion->externalStateData);
	}


	public function testAdvanceByExternalStateToCreatedFromApprovedState(IModel $appOrm, PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->createInOrm($appOrm, Payment::class, PaymentEntityFactory::createApprovedPayment());

		$exception = Assert::exception(
			function () use ($paymentService, $transaction, $payment) {
				$paymentService->advanceByExternalState(
					$transaction,
					$payment,
					new ExternalState(DummyExternalStateCodeEnum::CREATED(), [])
				);
			},
			InvalidPaymentInternalStateTransitionException::class,
			'Invalid transition from internal state \'approved\' to internal state \'created\'.'
		);

		Assert::type(PaymentException::class, $exception);
		Assert::same($payment, $exception->getPayment());
		Assert::same(InternalStateCodeEnum::CREATED(), $exception->getNewInternalState()->getCode());
		Assert::null($exception->getNewInternalState()->getFailureReason());
	}


	public function testAdvanceByExternalStateToApprovedFromFailedState(IModel $appOrm, PaymentService $paymentService)
	{
		$transaction = Mockery::mock(Transaction::class);
		$payment = $this->entityGenerator->createInOrm($appOrm, Payment::class, PaymentEntityFactory::createFailedPayment());

		$exception = Assert::exception(
			function () use ($paymentService, $transaction, $payment) {
				$paymentService->advanceByExternalState(
					$transaction,
					$payment,
					new ExternalState(DummyExternalStateCodeEnum::APPROVED(), [])
				);
			},
			InvalidPaymentInternalStateTransitionException::class,
			'Invalid transition from internal state \'failed\' to internal state \'approved\'.'
		);

		Assert::type(PaymentException::class, $exception);
		Assert::same($payment, $exception->getPayment());
		Assert::same(InternalStateCodeEnum::APPROVED(), $exception->getNewInternalState()->getCode());
		Assert::null($exception->getNewInternalState()->getFailureReason());
	}
}


PaymentServiceTest::run($containerFactory);
