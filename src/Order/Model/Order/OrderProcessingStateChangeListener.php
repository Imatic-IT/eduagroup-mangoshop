<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Transaction;

interface OrderProcessingStateChangeListener
{
	public function handleOrderProcessingStateChange(Transaction $transaction, OrderProcessing $orderProcessing);
}
