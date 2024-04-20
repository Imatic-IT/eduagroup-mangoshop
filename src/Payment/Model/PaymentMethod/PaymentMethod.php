<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read string $code
 * @property-read bool   $enabled
 */
abstract class PaymentMethod extends Entity
{
	public function __construct(string $code)
	{
		parent::__construct();
		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('enabled', true);
	}


	public function setEnabled(bool $enabled): void
	{
		$this->setReadOnlyValue('enabled', $enabled);
	}


	abstract public function createExternalStateCode(string $externalStateCodeValue): IExternalStateCodeEnum;


	/**
	 * @throws InvalidPaymentExternalStateTransitionException
	 */
	abstract public function getInternalState(Payment $payment, ExternalState $externalState): InternalState;
}
