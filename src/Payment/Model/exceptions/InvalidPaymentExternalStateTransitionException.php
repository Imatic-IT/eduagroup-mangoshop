<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use Mangoweb\ExceptionResponsibility\ResponsibilityThirdParty;
use Throwable;


class InvalidPaymentExternalStateTransitionException extends PaymentException implements ResponsibilityThirdParty
{
	/** @var ExternalState */
	private $newExternalState;

	/** @var string[] */
	private $allowedNextExternalStateCodes;


	/**
	 * @param string[] $allowedNextExternalStateCodes
	 */
	public function __construct(Payment $payment, ExternalState $newExternalState, array $allowedNextExternalStateCodes, ?Throwable $previous = null)
	{
		parent::__construct(
			$payment,
			sprintf(
				'Invalid transition from external state \'%s\' to external state \'%s\'. Allowed transitions are \'%s\'.',
				$payment->state->externalState ? $payment->state->externalState->getCode()->getValue() : 'null',
				$newExternalState->getCode()->getValue(),
				implode('\', \'', $allowedNextExternalStateCodes)
			),
			$previous
		);

		$this->newExternalState = $newExternalState;
		$this->allowedNextExternalStateCodes = $allowedNextExternalStateCodes;
	}


	public function getNewExternalState(): ExternalState
	{
		return $this->newExternalState;
	}


	/**
	 * @return string[]
	 */
	public function getAllowedNextExternalStateCodes(): array
	{
		return $this->allowedNextExternalStateCodes;
	}
}
