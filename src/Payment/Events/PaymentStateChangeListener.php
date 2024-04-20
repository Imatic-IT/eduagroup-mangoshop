<?php declare(strict_types = 1);

namespace MangoShop\Payment\Api;

use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Payment\Model\Payment;


interface PaymentStateChangeListener
{
	/**
	 * Invoked after Payment::$state has been changed.
	 */
	public function handlePaymentStateChange(Transaction $transaction, Payment $payment): void;
}
