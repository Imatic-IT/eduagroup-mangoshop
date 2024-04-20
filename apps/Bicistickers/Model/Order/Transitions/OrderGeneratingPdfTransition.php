<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;

class OrderGeneratingPdfTransition implements IOrderProcessingTransition
{
	public function createProcessing(Order $order): \MangoShop\Order\Model\OrderProcessing
	{
		assert($order->processing instanceof BicistickersOrderProcessing);
		assert($order->processing->isAllowed(BicistickersOrderProcessingStateEnum::GENERATING_PDFS()));

		return new BicistickersOrderProcessing($order, BicistickersOrderProcessingStateEnum::GENERATING_PDFS());
	}
}
