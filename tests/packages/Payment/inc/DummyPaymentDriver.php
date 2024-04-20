<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Inc;

use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\IPaymentDriver;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentException;
use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\Payment\Model\PaymentInitializationResponse;


class DummyPaymentDriver implements IPaymentDriver
{
	/**
	 * @throws PaymentException
	 */
	public function initialize(Payment $payment, PaymentInitializationRequest $data): PaymentInitializationResponse
	{
		static $externalIdentifierCounter = 1;

		return new PaymentInitializationResponse(
			$payment,
			sprintf('dummy#%d', $externalIdentifierCounter++),
			new ExternalState(DummyExternalStateCodeEnum::CREATED(), []),
			'https://dummy-gateway.url/'
		);
	}


	public function refund(Payment $payment): ExternalState
	{
		return new ExternalState(DummyExternalStateCodeEnum::FAILED(), [
			'failedReason' => 'refunded',
		]);
	}
}
