<?php declare(strict_types = 1);

namespace MangoShopTests\Money\Inc;

use MangoShop\Money\Model\Currency;
use MangoShop\Money\Model\Money;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;


class MoneyEntityFactory extends EntityFactory
{
	public function createCurrency(array $data): Currency
	{
		$this->verifyData(['code'], $data);
		$code = $data['code'] ?? 'CZK';

		return new Currency($code);
	}


	public function createMoney(array $data, EntityGenerator $generator): Money
	{
		$this->verifyData(['cents', 'currency'], $data);
		$cents = $data['cents'] ?? 12345;
		$currency = $generator->maybeCreate(Currency::class, $data['currency'] ?? []);

		return new Money($cents, $currency);
	}
}
