<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use Mangoweb\ExceptionResponsibility\ResponsibilityApp;
use Throwable;


class InvalidPaymentInternalStateTransitionException extends PaymentException implements ResponsibilityApp
{
	/** @var InternalState */
	private $newInternalState;


	public function __construct(Payment $payment, InternalState $newInternalState, ?Throwable $previous = null)
	{
		parent::__construct(
			$payment,
			sprintf(
				'Invalid transition from internal state \'%s\' to internal state \'%s\'.',
				$payment->state->internalState ? $payment->state->internalState->getCode()->getValue() : 'null',
				$newInternalState->getCode()->getValue()
			),
			$previous
		);

		$this->newInternalState = $newInternalState;
	}


	public function getNewInternalState(): InternalState
	{
		return $this->newInternalState;
	}
}
