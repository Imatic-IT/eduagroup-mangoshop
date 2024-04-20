<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Payment\Api\PaymentStateChangeListener;
use MangoShop\Payment\Model\Payment;

class OrderService implements PaymentStateChangeListener
{
	/** @var IOrderProcessingDriver */
	private $processingDriver;

	/** @var OrdersRepository */
	private $ordersRepository;

	/** @var OrderEventDispatcher */
	private $dispatcher;


	public function __construct(IOrderProcessingDriver $processingDriver, OrdersRepository $ordersRepository, OrderEventDispatcher $dispatcher)
	{
		$this->processingDriver = $processingDriver;
		$this->ordersRepository = $ordersRepository;
		$this->dispatcher = $dispatcher;
	}


	public function create(Transaction $transaction, Cart $cart, Payment $payment): Order
	{
		$order = new Order($cart, $payment);
		$transaction->persist($order);
		$this->dispatcher->dispatchOrderStateChange($transaction, $order, null);

		return $order;
	}


	public function handlePaymentStateChange(Transaction $transaction, Payment $payment): void
	{
		$order = $this->ordersRepository->getBy(['payment' => $payment]);
		if (!$order || $order->state !== OrderStateEnum::WAITING_FOR_PAYMENT()) {
			return;
		}
		$this->advanceByPaymentState($transaction, $order);
	}


	public function advanceProcessing(Transaction $transaction, Order $order, IOrderProcessingTransition $transition): void
	{
		$processing = $this->processingDriver->applyTransition($order, $transition);
		$order->advanceProcessing($processing);
		$this->dispatcher->dispatchOrderProcessingStateChange($transaction, $processing);
		if ($processing->isDispatched()) {
			$previousState = $order->state;
			$order->markDispatched();
			$this->dispatcher->dispatchOrderStateChange($transaction, $order, $previousState);
		}

		$transaction->persist($processing, $order);
	}


	public function cancelByShop(Transaction $transaction, Order $order): void
	{
		$previousState = $order->state;
		$order->markFailed(OrderFailureReasonEnum::CANCEL_SHOP());
		$this->dispatcher->dispatchOrderStateChange($transaction, $order, $previousState);

		$transaction->persist($order);
	}


	private function advanceByPaymentState(Transaction $transaction, Order $order): void
	{
		$previousState = $order->state;
		assert($previousState === OrderStateEnum::WAITING_FOR_PAYMENT());

		if ($order->payment->isApproved()) {
			$processing = $this->processingDriver->createInitialProcessing($order);
			$order->startProcessing($processing);
			$this->dispatcher->dispatchOrderStateChange($transaction, $order, $previousState);
		} elseif ($order->payment->isFailed()) {
			$order->markFailed(OrderFailureReasonEnum::PAYMENT_FAILED());
			$this->dispatcher->dispatchOrderStateChange($transaction, $order, $previousState);
		}

		$transaction->persist($order, $order->payment);
	}

}
