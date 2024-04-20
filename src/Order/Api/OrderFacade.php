<?php declare(strict_types = 1);

namespace MangoShop\Order\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Order\Model\Cart;
use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderService;
use MangoShop\Order\Model\OrdersRepository;
use MangoShop\Order\Model\PaymentInitializationRequestFactory;
use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\Payment\Model\PaymentInitializationResponse;


class OrderFacade
{
	/** @var OrdersRepository */
	private $ordersRepository;

	/** @var OrderService */
	private $orderService;

	/** @var PaymentFacade */
	private $paymentFacade;

	/** @var PaymentInitializationRequestFactory */
	private $paymentInitializationRequestFactory;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(
		OrdersRepository $ordersRepository,
		OrderService $orderService,
		PaymentFacade $paymentFacade,
		PaymentInitializationRequestFactory $paymentInitializationRequestFactory,
		TransactionManager $transactionManager
	) {
		$this->ordersRepository = $ordersRepository;
		$this->orderService = $orderService;
		$this->paymentFacade = $paymentFacade;
		$this->paymentInitializationRequestFactory = $paymentInitializationRequestFactory;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $id): Order
	{
		$order = $this->ordersRepository->getById($id);
		if ($order === null) {
			throw new EntityNotFoundException(Order::class, $id);
		}
		return $order;
	}


	public function create(Cart $cart): Order
	{
		$transaction = $this->transactionManager->begin();

		assert($cart->paymentMethod !== null);
		$payment = $this->paymentFacade->create(
			$cart->paymentMethod->id,
			$cart->context->currency->id,
			$cart->totalPrice->getCents(),
			$cart->context->locale->id
		);

		$order = $this->orderService->create($transaction, $cart, $payment);

		$this->transactionManager->flush($transaction);

		return $order;
	}


	public function initializePayment(int $orderId): PaymentInitializationResponse
	{
		$transaction = $this->transactionManager->begin();

		$order = $this->getById($orderId);
		$request = $this->paymentInitializationRequestFactory->create($order);
		$response = $this->paymentFacade->initialize($order->payment->id, $request);

		$this->transactionManager->flush($transaction);
		return $response;
	}


	public function advanceProcessing(int $orderId, IOrderProcessingTransition $transition): Order
	{
		$transaction = $this->transactionManager->begin();

		$order = $this->getById($orderId);
		$this->orderService->advanceProcessing($transaction, $order, $transition);

		$this->transactionManager->flush($transaction);

		return $order;
	}


	public function cancelByShop(int $orderId): Order
	{
		$transaction = $this->transactionManager->begin();

		$order = $this->getById($orderId);

		if ($order->payment->isApproved()) {
			$this->paymentFacade->refund($order->payment->id);
		}

		$this->orderService->cancelByShop($transaction, $order);

		$this->transactionManager->flush($transaction);

		return $order;
	}
}
