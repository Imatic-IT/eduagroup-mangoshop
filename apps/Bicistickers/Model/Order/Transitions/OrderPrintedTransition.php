<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;

class OrderPrintedTransition implements IOrderProcessingTransition
{
	public function createProcessing(Order $order): \MangoShop\Order\Model\OrderProcessing
	{
		assert($order->processing instanceof BicistickersOrderProcessing);
		assert($order->processing->isAllowed(BicistickersOrderProcessingStateEnum::PRINTED()));

		return new BicistickersOrderProcessing($order, BicistickersOrderProcessingStateEnum::PRINTED());
	}
}
