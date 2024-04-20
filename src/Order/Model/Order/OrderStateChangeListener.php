<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Transaction;

interface OrderStateChangeListener
{
	public function handleOrderStateChange(Transaction $transaction, Order $order, ?OrderStateEnum $previousState);
}
