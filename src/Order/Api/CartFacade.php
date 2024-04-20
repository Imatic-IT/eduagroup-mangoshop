<?php declare(strict_types = 1);

namespace MangoShop\Order\Api;

use MangoShop\Channel\Model\Channel;
use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Locale\Model\Locale;
use MangoShop\Order\Model\Cart;
use MangoShop\Order\Model\OrderContext;
use MangoShop\Order\Model\Session;
use MangoShop\Order\Model\SessionsRepository;

class CartFacade
{
	/** @var SessionsRepository */
	private $sessionsRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(SessionsRepository $sessionsRepository, TransactionManager $transactionManager)
	{
		$this->sessionsRepository = $sessionsRepository;
		$this->transactionManager = $transactionManager;
	}


	/**
	 * @throws EntityNotFoundException
	 */
	public function getCart(string $sessionToken, Channel $channel, ?Locale $locale = null): Cart
	{
		$session = $this->getSession($sessionToken);
		$locale = $locale ?? $channel->defaultLocale;
		$cart = $session->cart;
		if (!$cart || $cart->context->channel !== $channel || $cart->context->locale !== $locale) {
			$cart = $this->createCart($session, $channel, $locale);
		}

		return $cart;
	}


	/**
	 * @throws EntityNotFoundException
	 */
	public function dropCartItems(string $sessionToken): void
	{
		$session = $this->getSession($sessionToken);
		if (!$session->cart) {
			return;
		}

		$updateRequest = (new CartUpdateRequest($session->cart))
			->withoutProductItems()
			->withoutProductItems();

		$transaction = $this->transactionManager->begin();

		$cart = $this->createCartFromRequest($updateRequest);
		$session->setCart($cart);

		$transaction->persist($session, $cart);
		$this->transactionManager->flush($transaction);
	}


	public function updateCart(CartUpdateRequest $updateRequest): Cart
	{
		if (!$updateRequest->isUpdated()) {
			return $updateRequest->getPreviousCart();
		}
		$transaction = $this->transactionManager->begin();

		$cart = $this->createCartFromRequest($updateRequest);
		$session = $cart->context->session;
		$session->setCart($cart);

		$transaction->persist($session, $cart);
		$this->transactionManager->flush($transaction);

		return $cart;
	}


	private function getSession(string $sessionToken): Session
	{
		$session = $this->sessionsRepository->getBy(['token' => $sessionToken]);
		if (!$session) {
			throw new EntityNotFoundException(Session::class);
		}
		return $session;
	}


	private function createCart(Session $session, Channel $channel, Locale $locale): Cart
	{
		$orderContext = new OrderContext($channel, $channel->currency, $locale, $session);
		if (!$session->cart) {
			$cart = new Cart($orderContext);
		} else {
			$previousCart = $session->cart;
			$cart = $previousCart->withContext($orderContext);
		}

		$transaction = $this->transactionManager->begin();
		$session->setCart($cart);
		$transaction->persist($session, $cart);
		$this->transactionManager->flush($transaction);

		return $cart;
	}


	private function createCartFromRequest(CartUpdateRequest $request): Cart
	{
		assert($request->isUpdated());
		return new Cart(
			$request->getContext(),
			$request->getCustomer(),
			$request->getShippingInfo(),
			$request->getShippingMethod(),
			$request->getBillingInfo(),
			$request->getPaymentMethod(),
			$request->getPreviousCart(),
			$request->getProductItems(),
			$request->getPromotions()
		);
	}
}
