<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;


interface IOrderProcessingTransition
{
	public function createProcessing(Order $order): OrderProcessing;
}
