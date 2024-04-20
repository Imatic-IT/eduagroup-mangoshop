<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


interface IPaymentDriver
{
	/**
	 * @throws PaymentException
	 */
	public function initialize(Payment $payment, PaymentInitializationRequest $data): PaymentInitializationResponse;

	/**
	 * @throws PaymentException
	 */
	public function refund(Payment $payment): ExternalState;
}
