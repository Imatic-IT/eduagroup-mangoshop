<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Payment\Api\PaymentStateChangeListener;


class PaymentService
{
	/** @var PaymentStateChangeListener[] */
	private $listeners = [];


	public function registerPaymentStateChangeListener(PaymentStateChangeListener $listener): void
	{
		$this->listeners[] = $listener;
	}


	/**
	 * @throws PaymentException
	 */
	public function initializeExternalState(
		Transaction $transaction,
		Payment $payment,
		string $externalIdentifier,
		ExternalState $externalState
	): void {
		$payment->initializeExternalState($externalIdentifier, $externalState);
		$transaction->persist($payment);

		$this->invokePaymentStateChangeListeners($transaction, $payment);
	}


	/**
	 * @throws PaymentException
	 */
	public function advanceByExternalState(
		Transaction $transaction,
		Payment $payment,
		ExternalState $externalState
	): void {
		assert(!$externalState->equals($payment->state->externalState));
		$internalState = $payment->paymentMethod->getInternalState($payment, $externalState);

		if ($internalState->equals($payment->state->internalState)) {
			$payment->advanceExternalState($externalState);

		} elseif ($payment->isCreated() && $internalState->getCode() === InternalStateCodeEnum::APPROVED()) {
			$payment->markApproved($externalState);

		} elseif (($payment->isCreated() || $payment->isApproved()) && $internalState->getCode() === InternalStateCodeEnum::FAILED()) {
			assert($internalState->getFailureReason() !== null);
			$payment->markFailed($internalState->getFailureReason(), $externalState);

		} else {
			throw new InvalidPaymentInternalStateTransitionException($payment, $internalState);
		}

		$transaction->persist($payment);

		$this->invokePaymentStateChangeListeners($transaction, $payment);
	}


	private function invokePaymentStateChangeListeners(Transaction $transaction, Payment $payment): void
	{
		foreach ($this->listeners as $listener) {
			$listener->handlePaymentStateChange($transaction, $payment);
		}
	}
}
