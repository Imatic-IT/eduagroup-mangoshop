<?php declare(strict_types = 1);

namespace MangoShopTests\PaymentGoPay\Inc;

use MangoShop\Payment\Model\PaymentMethodsRepository;
use MangoShop\PaymentGoPay\Model\GoPayPaymentMethod;
use MangoShopTests\EntityFactory;


class GoPayPaymentMethodFactory extends EntityFactory
{
	public function createGoPayPaymentMethod(array $data): GoPayPaymentMethod
	{
		$this->verifyData(['code'], $data);
		$code = $data['code'] ?? 'gopay';
		PaymentMethodsRepository::registerPaymentMethod($code, GoPayPaymentMethod::class);

		return new GoPayPaymentMethod($code);
	}
}
