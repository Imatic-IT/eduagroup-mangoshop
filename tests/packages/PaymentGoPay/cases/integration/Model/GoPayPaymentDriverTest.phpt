<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Integration\Model;

use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\FailureReasonEnum;
use MangoShop\Payment\Model\GoPayRequestFailedException;
use MangoShop\Payment\Model\InternalStateCodeEnum;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentException;
use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\Payment\Model\PaymentOrderItem;
use MangoShop\Payment\Model\PaymentOrderItemTypeEnum;
use MangoShop\PaymentGoPay\Model\GoPayPaymentMethod;
use MangoShop\PaymentGoPay\Model\GoPayStateCodeEnum;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Payment\Inc\PaymentEntityFactory;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class GoPayPaymentDriverTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testInitializeWithEmptyInitializationRequest(PaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, [
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
		]);

		$response = $facade->initialize($payment->id, new PaymentInitializationRequest(null, null, []));
		Assert::match('%i%', $response->getPayment()->externalIdentifier);
		Assert::same(InternalStateCodeEnum::CREATED(), $response->getPayment()->state->internalState->getCode());
		Assert::same(GoPayStateCodeEnum::CREATED(), $response->getPayment()->state->externalState->getCode());
	}


	public function testInitializeWithComplexInitializationRequest(PaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, [
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'amount' => [
				'cents' => 800, // 300 + 700 - 200
				'currency' => [
					'code' => 'CZK',
				],
			],
		]);

		$response = $facade->initialize(
			$payment->id,
			new PaymentInitializationRequest('john@example.com', 'O123456', [
				new PaymentOrderItem(
					PaymentOrderItemTypeEnum::PRODUCT(),
					'https://eshop.example.com/12345',
					'12345',
					5,
					'Nice Shoes',
					300
				),
				new PaymentOrderItem(
					PaymentOrderItemTypeEnum::SHIPPING(),
					null,
					null,
					1,
					'Planet Express',
					700
				),
				new PaymentOrderItem(
					PaymentOrderItemTypeEnum::PROMOTION(),
					null,
					null,
					1,
					'Summer Sale',
					-200
				),
			])
		);

		Assert::match('%i%', $response->getPayment()->externalIdentifier);
		Assert::same(InternalStateCodeEnum::CREATED(), $response->getPayment()->state->internalState->getCode());
		Assert::same(GoPayStateCodeEnum::CREATED(), $response->getPayment()->state->externalState->getCode());
	}


	/**
	 * @param \GoPay\Payments|\Mockery\MockInterface $goPayApi
	 */
	public function testRefund(PaymentFacade $facade, \GoPay\Payments $goPayApi)
	{
		$goPayApi->shouldReceive('refundPayment')
			->withArgs(['3055641186', 12345])
			->andReturn($this->createMockResponse('{"id":3055641186,"result":"FINISHED"}'));

		$goPayApi->shouldReceive('getStatus')
			->withArgs(['3055641186'])
			->andReturn($this->createMockResponse('{"id":3055641186,"order_number":"ON-818851862-1516185482522","state":"REFUNDED","payment_instrument":"PAYMENT_CARD","amount":12345,"currency":"CZK","payer":{"allowed_payment_instruments":["PAYMENT_CARD"],"default_payment_instrument":"PAYMENT_CARD","payment_card":{"card_number":"418803******0003","card_expiration":"2103","card_brand":"VISA Electron","card_issuer_country":"CZE","card_issuer_bank":"KOMERCNI BANKA, A.S."},"contact":{"email":"thomas@gmail.com","country_code":"CZE"}},"target":{"type":"ACCOUNT","goid":8452810696},"lang":"cs","gw_url":"https://gw.sandbox.gopay.com/gw/v3/3a4718c92ad2f1554aa50ce6fc60c28f"}'));

		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => '3055641186',
			'externalState' => new ExternalState(GoPayStateCodeEnum::PAID(), []),
		]));

		$payment = $facade->refund($payment->id);
		Assert::same(InternalStateCodeEnum::FAILED(), $payment->state->internalState->getCode());
		Assert::same(FailureReasonEnum::REFUNDED(), $payment->state->internalState->getFailureReason());
		Assert::same(GoPayStateCodeEnum::REFUNDED(), $payment->state->externalState->getCode());
		Assert::same(['subState' => null], $payment->state->externalState->getData());
	}


	/**
	 * @param \GoPay\Payments|\Mockery\MockInterface $goPayApi
	 */
	public function testRefundWithFailedResponse(PaymentFacade $facade, \GoPay\Payments $goPayApi)
	{
		$mockResponse = $this->createMockResponse('{}', 0);
		$goPayApi->shouldReceive('refundPayment')
			->withArgs(['3055641186', 12345])
			->andReturn($mockResponse);

		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment([
			'paymentMethod' => $this->entityGenerator->create(GoPayPaymentMethod::class),
			'externalIdentifier' => '3055641186',
			'externalState' => new ExternalState(GoPayStateCodeEnum::PAID(), []),
		]));

		$exception = Assert::exception(
			function () use ($facade, $payment) {
				$facade->refund($payment->id);
			},
			GoPayRequestFailedException::class
		);

		Assert::type(PaymentException::class, $exception);
		Assert::same($payment->id, $exception->getPayment()->id);
		Assert::same($mockResponse, $exception->getResponse());
	}


	private function createMockResponse(string $rawBody, int $statusCode = 200)
	{
		$response = new \GoPay\Http\Response($rawBody);
		$response->statusCode = $statusCode;
		$response->json = json_decode($rawBody, true);

		return $response;
	}
}


GoPayPaymentDriverTest::run($containerFactory);
