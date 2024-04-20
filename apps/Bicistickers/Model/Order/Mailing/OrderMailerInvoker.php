<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderStateChangeListener;
use MangoShop\Order\Model\OrderStateEnum;

class OrderMailerInvoker implements OrderStateChangeListener
{
	/** @var OrderMailer */
	private $orderMailer;


	public function __construct(OrderMailer $orderMailer)
	{
		$this->orderMailer = $orderMailer;
	}


	public function handleOrderStateChange(Transaction $transaction, Order $order, ?OrderStateEnum $previousState)
	{
		if ($order->state === OrderStateEnum::PROCESSING() && $previousState === OrderStateEnum::WAITING_FOR_PAYMENT()) {
			$this->orderMailer->sendOrderSummary($order);
		}
		if ($order->state === OrderStateEnum::DISPATCHED() && $previousState !== OrderStateEnum::DISPATCHED()) {
			$this->orderMailer->sendOrderDispatchInfo($order);
		}
	}
}
