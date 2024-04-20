<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\Order;

interface IOrderMailContentFactory
{
	public function createSummaryContent(Order $order): OrderMailContent;

	public function createDispatchInfoContent(Order $order): OrderMailContent;

	public function createPaymentRequestContent(Order $order): OrderMailContent;
}
