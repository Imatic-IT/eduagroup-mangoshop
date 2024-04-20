<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\Order;

class DummyOrderMailContentFactory implements IOrderMailContentFactory
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
