<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Inc;

use MangoShop\Order\Model\IOrderProcessingDriver;
use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderProcessing;


class TestOrderProcessingDriver implements IOrderProcessingDriver
{
	public function createInitialProcessing(Order $order): OrderProcessing
	{
		assert($order->processing === null);
		return new TestOrderProcessing($order, TestOrderProcessingStateEnum::CREATED());
	}


	public function applyTransition(Order $order, IOrderProcessingTransition $transition): OrderProcessing
	{
		return $transition->createProcessing($order);
	}
}
