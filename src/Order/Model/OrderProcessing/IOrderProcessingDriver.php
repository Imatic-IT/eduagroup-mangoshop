<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;


interface IOrderProcessingDriver
{
	public function createInitialProcessing(Order $order): OrderProcessing;

	public function applyTransition(Order $order, IOrderProcessingTransition $transition): OrderProcessing;
}
