<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


class PaymentDriverProvider
{
	/**
	 * @var array<string, IPaymentDriver>
	 */
	private $registry = [];


	public function registerPaymentDriver(string $paymentMethodCode, IPaymentDriver $paymentDriver): void
	{
		assert(!isset($this->registry[$paymentMethodCode]));
		$this->registry[$paymentMethodCode] = $paymentDriver;
	}


	public function getPaymentDriver(Payment $payment): IPaymentDriver
	{
		$paymentMethodCode = $payment->paymentMethod->code;
		assert(isset($this->registry[$paymentMethodCode]));

		return $this->registry[$paymentMethodCode];
	}
}
