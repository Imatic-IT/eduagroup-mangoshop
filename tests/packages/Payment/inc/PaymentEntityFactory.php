<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Inc;

use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Money;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\FailureReasonEnum;
use MangoShop\Payment\Model\InternalStateCodeEnum;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Payment\Model\PaymentMethodsRepository;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;


class PaymentEntityFactory extends EntityFactory
{
	public function createPaymentMethod(array $data): DummyPaymentMethod
	{
		$this->verifyData(['code'], $data);
		$code = $data['code'] ?? $this->counter(PaymentMethod::class, '__dummy');
		PaymentMethodsRepository::registerPaymentMethod($code, DummyPaymentMethod::class);

		return new DummyPaymentMethod($code);
	}


	public function createPayment(array $data, EntityGenerator $generator): Payment
	{
		$this->verifyData(['paymentMethod', 'amount', 'locale', 'externalIdentifier', 'externalState', 'internalStateCode', 'internalStateFailureReason'], $data);
		$paymentMethod = $generator->maybeCreate(PaymentMethod::class, $data['paymentMethod'] ?? []);
		$amount = $generator->maybeCreate(Money::class, $data['amount'] ?? []);
		$locale = $generator->maybeCreate(Locale::class, $data['locale'] ?? []);

		$payment = new Payment($paymentMethod, $amount, $locale);

		if (isset($data['externalIdentifier'])) {
			$payment->initializeExternalState(
				$data['externalIdentifier'],
				$data['externalState'] ?? new ExternalState(DummyExternalStateCodeEnum::CREATED(), [])
			);
		}

		if (isset($data['internalStateCode'])) {
			if ($data['internalStateCode'] === InternalStateCodeEnum::APPROVED()) {
				$payment->markApproved(
					$data['externalState'] ?? new ExternalState(DummyExternalStateCodeEnum::APPROVED(), [])
				);

			} elseif ($data['internalStateCode'] === InternalStateCodeEnum::FAILED()) {
				$failureReason = $data['internalStateFailureReason'] ?? FailureReasonEnum::UNKNOWN();
				$payment->markFailed(
					$failureReason,
					new ExternalState(
						DummyExternalStateCodeEnum::FAILED(),
						['internalStateFailureReason' => $failureReason->getValue()]
					)
				);

			} else {
				throw new \LogicException();
			}
		}

		return $payment;
	}


	public static function createInitializedPayment(array $data = [])
	{
		static $externalIdentifierCounter = 0;

		return $data + [
			'externalIdentifier' => sprintf('EI:%s', $externalIdentifierCounter++),
		];
	}


	public static function createApprovedPayment(array $data = [])
	{
		return self::createInitializedPayment($data) + [
			'internalStateCode' => InternalStateCodeEnum::APPROVED(),
		];
	}


	public static function createFailedPayment(array $data = [])
	{
		return self::createInitializedPayment($data) + [
			'internalStateCode' => InternalStateCodeEnum::FAILED(),
		];
	}
}
