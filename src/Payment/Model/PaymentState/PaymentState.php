<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use Mangoweb\Clock\Clock;


/**
 * @property-read PaymentMethod               $paymentMethod              {m:1 PaymentMethod, oneSided=true}
 * @property-read null|PaymentState           $previousVersion            {1:1 PaymentState, isMain=true, oneSided=true}
 * @property-read DateTimeImmutable           $createdAt
 *
 * @property-read InternalStateCodeEnum       $internalStateCode          {container \MangoShop\Core\NextrasOrm\EnumProperty}
 * @property-read null|FailureReasonEnum      $internalStateFailureReason {container \MangoShop\Core\NextrasOrm\EnumProperty}
 * @property-read null|string                 $externalStateCode
 * @property-read array                       $externalStateData          {container \MangoShop\Core\NextrasOrm\JsonProperty}
 *
 * @property-read InternalState               $internalState              {virtual}
 * @property-read null|ExternalState          $externalState              {virtual}
 */
class PaymentState extends Entity
{
	public function __construct(
		PaymentMethod $paymentMethod,
		?self $previousVersion,
		InternalState $internalState,
		?ExternalState $externalState
	) {
		parent::__construct();

		$this->setReadOnlyValue('paymentMethod', $paymentMethod);
		$this->setReadOnlyValue('previousVersion', $previousVersion);
		$this->setReadOnlyValue('internalStateCode', $internalState->getCode());
		$this->setReadOnlyValue('internalStateFailureReason', $internalState->getFailureReason());
		$this->setReadOnlyValue('externalStateCode', $externalState ? $externalState->getCode()->getValue() : null);
		$this->setReadOnlyValue('externalStateData', $externalState ? $externalState->getData() : []);
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	protected function getterInternalState(): InternalState
	{
		return new InternalState($this->internalStateCode, $this->internalStateFailureReason);
	}


	protected function getterExternalState(): ?ExternalState
	{
		return $this->externalStateCode
			? new ExternalState($this->paymentMethod->createExternalStateCode($this->externalStateCode), $this->externalStateData)
			: null;
	}


	public static function createInitialState(PaymentMethod $paymentMethod): self
	{
		return new self(
			$paymentMethod,
			null,
			new InternalState(InternalStateCodeEnum::CREATED(), null),
			null
		);
	}


	public function createInitializedExternalState(ExternalState $externalState): self
	{
		assert($this->internalStateCode === InternalStateCodeEnum::CREATED());
		assert($this->internalStateFailureReason === null);
		assert($this->externalStateCode === null);

		return new self(
			$this->paymentMethod,
			$this,
			$this->internalState,
			$externalState
		);
	}


	public function createAdvancedExternalState(ExternalState $externalState): self
	{
		assert($this->externalStateCode !== null);

		return new self(
			$this->paymentMethod,
			$this,
			$this->internalState,
			$externalState
		);
	}


	public function createApprovedState(ExternalState $externalState): self
	{
		assert($this->internalStateCode === InternalStateCodeEnum::CREATED());
		assert($this->internalStateFailureReason === null);

		return new self(
			$this->paymentMethod,
			$this,
			new InternalState(InternalStateCodeEnum::APPROVED(), null),
			$externalState
		);
	}


	public function createFailedState(FailureReasonEnum $failureReason, ExternalState $externalState): self
	{
		assert($this->internalStateCode === InternalStateCodeEnum::CREATED() || $this->internalStateCode === InternalStateCodeEnum::APPROVED());
		assert($this->internalStateFailureReason === null);

		return new self(
			$this->paymentMethod,
			$this,
			new InternalState(InternalStateCodeEnum::FAILED(), $failureReason),
			$externalState
		);
	}
}
