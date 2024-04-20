<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Currency;
use MangoShop\Money\Model\Money;


/**
 * @property-read PaymentMethod              $paymentMethod      {m:1 PaymentMethod, oneSided=true}
 * @property-read int                        $amountCents
 * @property-read Currency                   $amountCurrency     {m:1 Currency, oneSided=true}
 * @property-read Money                      $amount             {virtual}
 * @property-read Locale                     $locale             {m:1 Locale, oneSided=true}
 * @property-read PaymentState               $state              {m:1 PaymentState, oneSided=true}
 * @property-read null|string                $externalIdentifier
 * @property-read DateTimeImmutable          $createdAt
 * @property-read null|DateTimeImmutable     $approvedAt
 * @property-read null|DateTimeImmutable     $failedAt
 */
class Payment extends Entity
{
	public function __construct(PaymentMethod $paymentMethod, Money $amount, Locale $locale)
	{
		parent::__construct();
		$this->setReadOnlyValue('paymentMethod', $paymentMethod);
		$this->setReadOnlyValue('amountCents', $amount->getCents());
		$this->setReadOnlyValue('amountCurrency', $amount->getCurrency());
		$this->setReadOnlyValue('locale', $locale);
		$this->setReadOnlyValue('state', PaymentState::createInitialState($paymentMethod));
		$this->setReadOnlyValue('createdAt', $this->state->createdAt);
	}


	public function isCreated(): bool
	{
		return $this->state->internalStateCode === InternalStateCodeEnum::CREATED();
	}


	public function isApproved(): bool
	{
		return $this->state->internalStateCode === InternalStateCodeEnum::APPROVED();
	}


	public function isFailed(): bool
	{
		return $this->state->internalStateCode === InternalStateCodeEnum::FAILED();
	}


	public function initializeExternalState(string $externalIdentifier, ExternalState $externalState): void
	{
		assert($this->externalIdentifier === null);
		assert($this->state->internalStateCode === InternalStateCodeEnum::CREATED());
		assert($this->state->internalStateFailureReason === null);
		assert($this->state->externalStateCode === null);

		$state = $this->state->createInitializedExternalState($externalState);
		assert($state->previousVersion === $this->state);
		assert($state->internalStateCode === $this->state->internalStateCode);
		assert($state->internalStateFailureReason === $this->state->internalStateFailureReason);
		assert($state->externalStateCode !== null);

		$this->setReadOnlyValue('externalIdentifier', $externalIdentifier);
		$this->setReadOnlyValue('state', $state);
	}


	public function advanceExternalState(ExternalState $externalState): void
	{
		assert($this->externalIdentifier !== null);
		assert($this->state->externalStateCode !== null);

		$state = $this->state->createAdvancedExternalState($externalState);
		assert($state->previousVersion === $this->state);
		assert($state->internalStateCode === $this->state->internalStateCode);
		assert($state->internalStateFailureReason === $this->state->internalStateFailureReason);
		assert($state->externalStateCode !== null);

		$this->setReadOnlyValue('state', $state);
	}


	public function markApproved(ExternalState $externalState): void
	{
		assert($this->state->internalStateCode === InternalStateCodeEnum::CREATED());
		assert($this->state->internalStateFailureReason === null);
		assert($this->approvedAt === null);
		assert($this->failedAt === null);

		$state = $this->state->createApprovedState($externalState);
		assert($state->previousVersion === $this->state);
		assert($state->internalStateCode === InternalStateCodeEnum::APPROVED());
		assert($state->internalStateFailureReason === null);

		$this->setReadOnlyValue('state', $state);
		$this->setReadOnlyValue('approvedAt', $state->createdAt);
	}


	public function markFailed(FailureReasonEnum $failureReason, ExternalState $externalState): void
	{
		assert($this->state->internalStateCode === InternalStateCodeEnum::CREATED() || $this->state->internalStateCode === InternalStateCodeEnum::APPROVED());
		assert($this->state->internalStateFailureReason === null);
		assert($this->failedAt === null);

		$state = $this->state->createFailedState($failureReason, $externalState);
		assert($state->previousVersion === $this->state);
		assert($state->internalStateCode === InternalStateCodeEnum::FAILED());
		assert($state->internalStateFailureReason !== null);

		$this->setReadOnlyValue('state', $state);
		$this->setReadOnlyValue('failedAt', $state->createdAt);
	}


	protected function getterAmount(): Money
	{
		return new Money($this->amountCents, $this->amountCurrency);
	}
}
