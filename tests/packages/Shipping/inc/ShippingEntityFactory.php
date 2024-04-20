<?php declare(strict_types = 1);

namespace MangoShopTests\Shipping\Inc;

use MangoShop\Shipping\Model\ShippingMethod;
use MangoShopTests\EntityFactory;

class ShippingEntityFactory extends EntityFactory
{
	public function createShippingMethod(array $data): ShippingMethod
	{
		$this->verifyData(['code'], $data);

		$code = $data['code'] ?? 'DHL';

		return new ShippingMethod($code);
	}
}
