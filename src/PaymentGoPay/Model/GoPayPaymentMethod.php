<?php declare(strict_types = 1);

namespace MangoShop\PaymentGoPay\Model;

use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\FailureReasonEnum;
use MangoShop\Payment\Model\IExternalStateCodeEnum;
use MangoShop\Payment\Model\InternalState;
use MangoShop\Payment\Model\InternalStateCodeEnum;
use MangoShop\Payment\Model\InvalidPaymentExternalStateTransitionException;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentMethod;


class GoPayPaymentMethod extends PaymentMethod
{
	private const EXTERNAL_STATE_TRANSITIONS = [
		GoPayStateCodeEnum::CREATED => [
			GoPayStateCodeEnum::PAYMENT_METHOD_CHOSEN => true,
			GoPayStateCodeEnum::CANCELED => true,
			GoPayStateCodeEnum::TIMEOUTED => true,
			GoPayStateCodeEnum::PAID => true,
			GoPayStateCodeEnum::AUTHORIZED => true,
		],
		GoPayStateCodeEnum::PAYMENT_METHOD_CHOSEN => [
			GoPayStateCodeEnum::CANCELED => true,
			GoPayStateCodeEnum::TIMEOUTED => true,
			GoPayStateCodeEnum::PAID => true,
			GoPayStateCodeEnum::AUTHORIZED => true,
		],
		GoPayStateCodeEnum::AUTHORIZED => [
			GoPayStateCodeEnum::CANCELED => true,
			GoPayStateCodeEnum::PAID => true,
		],
		GoPayStateCodeEnum::PAID => [
			GoPayStateCodeEnum::REFUNDED => true,
			GoPayStateCodeEnum::PARTIALLY_REFUNDED => true,
		],
	];

	private const PAYMENT_STATE_MAP = [
		GoPayStateCodeEnum::CREATED => InternalStateCodeEnum::CREATED,
		GoPayStateCodeEnum::PAYMENT_METHOD_CHOSEN => InternalStateCodeEnum::CREATED,
		GoPayStateCodeEnum::AUTHORIZED => InternalStateCodeEnum::CREATED,
		GoPayStateCodeEnum::PAID => InternalStateCodeEnum::APPROVED,
		GoPayStateCodeEnum::CANCELED => InternalStateCodeEnum::FAILED,
		GoPayStateCodeEnum::TIMEOUTED => InternalStateCodeEnum::FAILED,
		GoPayStateCodeEnum::REFUNDED => InternalStateCodeEnum::FAILED,
		GoPayStateCodeEnum::PARTIALLY_REFUNDED => InternalStateCodeEnum::FAILED,
	];

	private const FAILURE_REASON_MAP = [
		GoPayStateCodeEnum::CANCELED => FailureReasonEnum::DENIED,
		GoPayStateCodeEnum::TIMEOUTED => FailureReasonEnum::TIMEOUTED,
		GoPayStateCodeEnum::REFUNDED => FailureReasonEnum::REFUNDED,
		GoPayStateCodeEnum::PARTIALLY_REFUNDED => FailureReasonEnum::UNKNOWN,
	];


	public function createExternalStateCode(string $externalStateCodeValue): IExternalStateCodeEnum
	{
		return GoPayStateCodeEnum::byValue($externalStateCodeValue);
	}


	public function getInternalState(Payment $payment, ExternalState $externalState): InternalState
	{
		assert($payment->paymentMethod === $this);
		assert($payment->state->externalState !== null);

		$activeExternalStateCodeValue = $payment->state->externalState->getCode()->getValue();
		$targetExternalStateCodeValue = $externalState->getCode()->getValue();

		if (!isset(self::EXTERNAL_STATE_TRANSITIONS[$activeExternalStateCodeValue][$targetExternalStateCodeValue])) {
			throw new InvalidPaymentExternalStateTransitionException(
				$payment,
				$externalState,
				array_keys(self::EXTERNAL_STATE_TRANSITIONS[$activeExternalStateCodeValue] ?? [])
			);
		}

		$internalStateCode = InternalStateCodeEnum::byValue(self::PAYMENT_STATE_MAP[$targetExternalStateCodeValue]);
		$failureReasonValue = self::FAILURE_REASON_MAP[$targetExternalStateCodeValue] ?? null;
		$failureReason = $failureReasonValue ? FailureReasonEnum::byValue($failureReasonValue) : null;

		return new InternalState($internalStateCode, $failureReason);
	}
}
