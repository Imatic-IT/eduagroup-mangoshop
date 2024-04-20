<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\IOrderProcessingDriver;
use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderProcessing;


class BicistickersOrderProcessingDriver implements IOrderProcessingDriver
{
	public function createInitialProcessing(Order $order): OrderProcessing
	{
		assert($order->processing === null);
		return new BicistickersOrderProcessing($order, BicistickersOrderProcessingStateEnum::CREATED());
	}


	public function applyTransition(Order $order, IOrderProcessingTransition $transition): OrderProcessing
	{
		return $transition->createProcessing($order);
	}
}
