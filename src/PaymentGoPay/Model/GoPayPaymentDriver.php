<?php declare(strict_types = 1);

namespace MangoShop\PaymentGoPay\Model;

use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\GoPayRequestFailedException;
use MangoShop\Payment\Model\IPaymentDriver;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\Payment\Model\PaymentInitializationResponse;
use MangoShop\Payment\Model\PaymentOrderItem;
use MangoShop\Payment\Model\PaymentOrderItemTypeEnum;


class GoPayPaymentDriver implements IPaymentDriver
{
	private const DATA_SUB_STATE_CODE = 'subState';

	/** @see https://doc.gopay.com/en/#lang */
	private const SUPPORTED_LANGUAGES = [
		\GoPay\Definition\Language::CZECH => true,
		\GoPay\Definition\Language::ENGLISH => true,
		\GoPay\Definition\Language::SLOVAK => true,
		\GoPay\Definition\Language::GERMAN => true,
		\GoPay\Definition\Language::RUSSIAN => true,
		\GoPay\Definition\Language::Polish => true,
		\GoPay\Definition\Language::Hungarian => true,
		\GoPay\Definition\Language::French => true,
		\GoPay\Definition\Language::Romanian => true,
	];

	/** @see https://doc.gopay.com/en/#type */
	private const ITEM_TYPE_MAP = [
		PaymentOrderItemTypeEnum::PRODUCT => \GoPay\Definition\Payment\PaymentItemType::ITEM,
		PaymentOrderItemTypeEnum::SHIPPING => \GoPay\Definition\Payment\PaymentItemType::DELIVERY,
		PaymentOrderItemTypeEnum::PROMOTION => \GoPay\Definition\Payment\PaymentItemType::DISCOUNT,
	];

	/** @var \GoPay\Payments */
	private $goPayApi;

	/** @var string */
	private $paymentMethodCode;

	/** @var string */
	private $returnEndpointUrl;

	/** @var string */
	private $notifyEndpointUrl;


	public function __construct(\GoPay\Payments $goPayApi, string $paymentMethodCode, string $returnEndpointUrl, string $notifyEndpointUrl)
	{
		$this->goPayApi = $goPayApi;
		$this->paymentMethodCode = $paymentMethodCode;
		$this->returnEndpointUrl = $returnEndpointUrl;
		$this->notifyEndpointUrl = $notifyEndpointUrl;
	}


	public function getPaymentMethodCode(): string
	{
		return $this->paymentMethodCode;
	}


	public function getReturnEndpointUrl(): string
	{
		return $this->returnEndpointUrl;
	}


	public function getNotifyEndpointUrl(): string
	{
		return $this->notifyEndpointUrl;
	}


	/**
	 * @see https://doc.gopay.com/en/#standard-payment
	 * @throws GoPayRequestFailedException
	 */
	public function initialize(Payment $payment, PaymentInitializationRequest $data): PaymentInitializationResponse
	{
		assert($payment->paymentMethod instanceof GoPayPaymentMethod);
		assert($payment->externalIdentifier === null);

		$response = $this->goPayApi->createPayment([
			'payer' => [
				'default_payment_instrument' => \GoPay\Definition\Payment\PaymentInstrument::PAYMENT_CARD,
				'allowed_payment_instruments' => [
					\GoPay\Definition\Payment\PaymentInstrument::PAYMENT_CARD,
				],
				'contact' => [
					'email' => $data->customerEmail,
				],
			],
			'amount' => $payment->amountCents,
			'currency' => $payment->amountCurrency->code,
			'lang' => $this->getLang($payment),
			'order_number' => $data->orderNumber,
			'items' => array_map(
				function (PaymentOrderItem $item): array {
					return [
						'type' => self::ITEM_TYPE_MAP[$item->type->getValue()],
						'product_url' => $item->productUrl,
						'ean' => $item->ean,
						'count' => $item->quantity,
						'name' => $item->name,
						'amount' => $item->totalAmountCents,
					];
				},
				$data->orderItems
			),
			'callback' => [
				'return_url' => $this->returnEndpointUrl,
				'notification_url' => $this->notifyEndpointUrl,
			],
			'additional_params' => [
				['name' => 'payment_id', 'value' => (string) $payment->id],
			],
		]);

		$this->requireSuccessfulResponse($payment, $response);

		$externalIdentifier = (string) $response->json['id'];
		$externalStateCode = GoPayStateCodeEnum::byValue($response->json['state']);
		$externalSubStateCode = $response->json['sub_state'] ?? null;
		$gatewayUrl = $response->json['gw_url'];

		$externalState = new ExternalState($externalStateCode, [
			self::DATA_SUB_STATE_CODE => $externalSubStateCode,
		]);

		return new PaymentInitializationResponse(
			$payment,
			$externalIdentifier,
			$externalState,
			$gatewayUrl
		);
	}


	/**
	 * @see https://doc.gopay.com/en/#status-of-the-payment
	 * @throws GoPayRequestFailedException
	 */
	public function refreshState(Payment $payment): ExternalState
	{
		assert($payment->paymentMethod instanceof GoPayPaymentMethod);
		assert($payment->externalIdentifier !== null);

		$response = $this->goPayApi->getStatus($payment->externalIdentifier);
		$this->requireSuccessfulResponse($payment, $response);

		$externalStateCode = GoPayStateCodeEnum::byValue($response->json['state']);
		$externalSubStateCode = $response->json['sub_state'] ?? null;

		return new ExternalState($externalStateCode, [
			self::DATA_SUB_STATE_CODE => $externalSubStateCode,
		]);
	}


	/**
	 * @see https://doc.gopay.com/en/#refund-of-the-payment-(cancelation)
	 * @throws GoPayRequestFailedException
	 */
	public function refund(Payment $payment): ExternalState
	{
		assert($payment->paymentMethod instanceof GoPayPaymentMethod);
		assert($payment->externalIdentifier !== null);

		$response = $this->goPayApi->refundPayment($payment->externalIdentifier, $payment->amountCents);
		$this->requireSuccessfulResponse($payment, $response);

		$refundResult = $response->json['result'];
		assert($refundResult === \GoPay\Definition\Response\Result::ACCEPTED || $refundResult === \GoPay\Definition\Response\Result::FINISHED);

		return $this->refreshState($payment);
	}


	private function requireSuccessfulResponse(Payment $payment, \GoPay\Http\Response $response): void
	{
		if ($response->hasSucceed()) {
			return;
		}

		throw new GoPayRequestFailedException($payment, $response);
	}


	private function getLang(Payment $payment): string
	{
		$lang = strtoupper($payment->locale->languageCode);
		return isset(self::SUPPORTED_LANGUAGES[$lang]) ? $lang : \GoPay\Definition\Language::ENGLISH;
	}
}
