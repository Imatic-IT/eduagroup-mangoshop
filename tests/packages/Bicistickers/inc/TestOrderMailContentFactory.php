<?php declare(strict_types = 1);

namespace MangoShopTests\Bicistickers\Inc;

use MangoShop\Bicistickers\Model\IOrderMailContentFactory;
use MangoShop\Bicistickers\Model\OrderMailContent;
use MangoShop\Order\Model\Order;

class TestOrderMailContentFactory implements IOrderMailContentFactory
{
	public function createSummaryContent(Order $order): OrderMailContent
	{
		return new OrderMailContent(sprintf('Order #%d created', $order->id), 'body');
	}


	public function createDispatchInfoContent(Order $order): OrderMailContent
	{
		return new OrderMailContent(sprintf('Order #%d dispatched', $order->id), 'body');
	}


	public function createPaymentRequestContent(Order $order): OrderMailContent
	{
		return new OrderMailContent(sprintf('Order #%d is waiting for a payment', $order->id), 'body');
	}
}
