<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Inc;

use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\FailureReasonEnum;
use MangoShop\Payment\Model\IExternalStateCodeEnum;
use MangoShop\Payment\Model\InternalState;
use MangoShop\Payment\Model\InternalStateCodeEnum;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentMethod;


class DummyPaymentMethod extends PaymentMethod
{
	public function createExternalStateCode(string $externalStateCodeValue): IExternalStateCodeEnum
	{
		return DummyExternalStateCodeEnum::byValue($externalStateCodeValue);
	}


	public function getInternalState(Payment $payment, ExternalState $externalState): InternalState
	{
		$externalStateCode = $externalState->getCode();
		$externalStateData = $externalState->getData();

		$code = InternalStateCodeEnum::byValue($externalStateCode->getValue());
		$failureReason = isset($externalStateData['failureReason']) ? FailureReasonEnum::byValue($externalStateData['failureReason']) : null;

		return new InternalState($code, $failureReason);
	}
}
