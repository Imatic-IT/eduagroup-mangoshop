<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Transaction;

class OrderEventDispatcher
{
	/** @var OrderStateChangeListener[] */
	private $stateChangeListeners = [];

	/** @var OrderProcessingStateChangeListener[] */
	private $processingStateChangeListeners = [];


	public function registerOrderStateChangeListener(OrderStateChangeListener $listener): void
	{
		$this->stateChangeListeners[] = $listener;
	}


	public function registerOrderProcessingStateChangeListener(OrderProcessingStateChangeListener $listener): void
	{
		$this->processingStateChangeListeners[] = $listener;
	}


	public function dispatchOrderStateChange(Transaction $transaction, Order $order, ?OrderStateEnum $previousState): void
	{
		foreach ($this->stateChangeListeners as $listener) {
			$listener->handleOrderStateChange($transaction, $order, $previousState);
		}
	}


	public function dispatchOrderProcessingStateChange(Transaction $transaction, OrderProcessing $processing): void
	{
		foreach ($this->processingStateChangeListeners as $listener) {
			$listener->handleOrderProcessingStateChange($transaction, $processing);
		}
	}
}
