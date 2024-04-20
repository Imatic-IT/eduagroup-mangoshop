<?php declare(strict_types = 1);

namespace MangoShopTests\Channel\Inc;

use MangoShop\Channel\Model\Channel;
use MangoShop\Channel\Model\CheckoutOptionGroup;
use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Currency;
use MangoShop\Product\Model\ProductPricingGroup;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;

class ChannelEntityFactory extends EntityFactory
{
	public function createChannel(array $data, EntityGenerator $generator): Channel
	{
		$this->verifyData(['code', 'name', 'locale', 'currency', 'pricingGroup'], $data);
		$code = $data['code'] ?? $this->counter('code', 'CZ');
		$name = $data['name'] ?? $this->counter('name', 'Czech channel ');

		$locale = $generator->maybeCreate(Locale::class, $data['locale'] ?? true);
		assert($locale instanceof Locale);

		$currency = $generator->maybeCreate(Currency::class, $data['currency'] ?? true);

		$pricingGroup = $generator->maybeCreate(ProductPricingGroup::class, $data['pricingGroup'] ?? true, ['currency' => $currency]);
		assert($pricingGroup instanceof ProductPricingGroup);

		$checkoutOptionGroup = $generator->create(CheckoutOptionGroup::class, ['currency' => $currency]);
		assert($checkoutOptionGroup instanceof CheckoutOptionGroup);

		return new Channel($code, $name, $locale, $pricingGroup, $checkoutOptionGroup);
	}


	public function createCheckoutOptionGroup(array $data, EntityGenerator $generator)
	{
		$this->verifyData(['currency'], $data);
		$currency = $generator->maybeCreate(Currency::class, $data['currency']);
		assert($currency instanceof Currency);

		return new CheckoutOptionGroup('default', $currency);
	}
}
