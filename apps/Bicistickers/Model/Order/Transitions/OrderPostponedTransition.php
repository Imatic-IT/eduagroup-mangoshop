<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;

class OrderPostponedTransition implements IOrderProcessingTransition
{
	public function createProcessing(Order $order): \MangoShop\Order\Model\OrderProcessing
	{
		assert($order->processing instanceof BicistickersOrderProcessing);
		assert($order->processing->isAllowed(BicistickersOrderProcessingStateEnum::POSTPONED()));

		return new BicistickersOrderProcessing($order, BicistickersOrderProcessingStateEnum::POSTPONED());
	}
}
