<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Inc;

use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderProcessing;

class TestOrderProcessingTransition implements IOrderProcessingTransition
{
	/** @var TestOrderProcessingStateEnum */
	private $targetState;


	public function __construct(TestOrderProcessingStateEnum $targetState)
	{
		$this->targetState = $targetState;
	}


	public function createProcessing(Order $order): OrderProcessing
	{
		assert($order->processing instanceof TestOrderProcessing);
		assert($order->processing->isAllowed($this->targetState));

		return new TestOrderProcessing($order, $this->targetState);
	}
}
