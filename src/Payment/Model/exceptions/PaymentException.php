<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use RuntimeException;
use Throwable;


abstract class PaymentException extends RuntimeException
{
	/** @var Payment */
	private $payment;


	public function __construct(Payment $payment, string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->payment = $payment;
	}


	public function getPayment(): Payment
	{
		return $this->payment;
	}
}
