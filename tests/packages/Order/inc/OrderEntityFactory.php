<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Inc;

use MangoShop\Address\Model\Address;
use MangoShop\Address\Model\Email;
use MangoShop\Address\Model\Phone;
use MangoShop\Channel\Model\Channel;
use MangoShop\Order\Model\Cart;
use MangoShop\Order\Model\CartProductItemDto;
use MangoShop\Order\Model\CartPromotionDto;
use MangoShop\Order\Model\Customer;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderBillingInfo;
use MangoShop\Order\Model\OrderContext;
use MangoShop\Order\Model\OrderProcessing;
use MangoShop\Order\Model\OrderShippingInfo;
use MangoShop\Order\Model\OrderStateEnum;
use MangoShop\Order\Model\Session;
use MangoShop\Payment\Model\ExternalState;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Product\Model\ProductVariant;
use MangoShop\Promotion\Model\PromotionCoupon;
use MangoShop\Shipping\Model\ShippingMethod;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;
use MangoShopTests\EntityReference;
use MangoShopTests\Payment\Inc\DummyExternalStateCodeEnum;


class OrderEntityFactory extends EntityFactory
{
	public function createSession(array $data): Session
	{
		$this->verifyData([], $data);
		return new Session();
	}


	public function createCustomer(array $data): Customer
	{
		$this->verifyData(['email'], $data);
		$email = $data['email'] ?? $this->counter(Customer::class, 'john', '@gmail.com');

		return new Customer(new Email($email));
	}


	public function createOrderContext(array $data, EntityGenerator $generator): OrderContext
	{
		$this->verifyData(['channel'], $data);
		$channel = $generator->maybeCreate(Channel::class, $data['channel'] ?? []);
		$currency = $channel->currency;
		$locale = $channel->defaultLocale;
		$session = $generator->create(Session::class);

		return new OrderContext($channel, $currency, $locale, $session);
	}


	public function createOrderShippingInfo(array $data, EntityGenerator $generator): OrderShippingInfo
	{
		$this->verifyData(['address', 'phone'], $data);
		$address = $generator->maybeCreate(Address::class, $data['address'] ?? []);
		$phone = isset($data['phone']) ? new Phone($data['phone']) : null;

		return new OrderShippingInfo($address, $phone);
	}


	public function createOrderBillingInfo(array $data, EntityGenerator $generator): OrderBillingInfo
	{
		$this->verifyData(['address'], $data);

		$address = $generator->maybeCreate(Address::class, $data['address'] ?? []);

		return new OrderBillingInfo($address, null, null);
	}


	public function createCart(array $data, EntityGenerator $generator): Cart
	{
		$this->verifyData(['context', 'customer', 'shippingInfo', 'paymentMethod', 'shippingMethod', 'billingInfo', 'productItems', 'promotionItems'], $data);
		$orderContext = $generator->maybeCreate(OrderContext::class, $data['context'] ?? []);
		$customer = $generator->maybeCreate(Customer::class, $data['customer'] ?? []);
		$shippingInfo = $generator->maybeCreate(OrderShippingInfo::class, $data['shippingInfo'] ?? []);
		$shippingMethod = $generator->maybeCreate(ShippingMethod::class, $data['shippingMethod'] ?? []);
		$billingInfo = $generator->maybeCreate(OrderBillingInfo::class, $data['billingInfo'] ?? []);
		$paymentMethod = $generator->maybeCreate(PaymentMethod::class, $data['paymentMethod'] ?? []);

		$productItems = $generator->createList(CartProductItemDto::class, $data['productItems'] ?? 2);
		$promotionItems = $generator->createList(CartPromotionDto::class, $data['promotionItems'] ?? 0);

		return new Cart($orderContext, $customer, $shippingInfo, $shippingMethod, $billingInfo, $paymentMethod, null, $productItems, $promotionItems);
	}


	public function createCartProductItemDto(array $data, EntityGenerator $generator): CartProductItemDto
	{
		$this->verifyData(['variant', 'quantity', 'configuration'], $data);

		$productVariant = $generator->maybeCreate(ProductVariant::class, $data['variant'] ?? []);
		$quantity = $data['quantity'] ?? 1;
		$configuration = $data['configuration'] ?? [];

		return new CartProductItemDto($productVariant, $quantity, $configuration);
	}


	public function createCartPromotionDto(array $data, EntityGenerator $generator): CartPromotionDto
	{
		$this->verifyData(['promotionCoupon'], $data);
		$promotionCoupon = $generator->maybeCreate(PromotionCoupon::class, $data['promotionCoupon'] ?? []);

		return new CartPromotionDto($promotionCoupon, 1);
	}


	public function createOrder(array $data, EntityGenerator $generator): Order
	{
		$this->verifyData(['cart', 'state', 'processing'], $data);

		$cart = $generator->maybeCreate(Cart::class, $data['cart'] ?? true);
		$payment = $generator->create(Payment::class, ['amount' => $cart->totalPrice, 'paymentMethod' => $cart->paymentMethod]);

		$order = new Order($cart, $payment);

		switch ($data['state'] ?? null) {
			case null:
				break;

			case OrderStateEnum::PROCESSING():
				$processing = $generator->maybeCreate(OrderProcessing::class, $data['processing'] ?? true, ['order' => $order]);
				$order->payment->markApproved(new ExternalState(DummyExternalStateCodeEnum::APPROVED(), []));
				$order->startProcessing($processing);
				break;

			default:
				throw new \LogicException('not implemented');
		}

		return $order;
	}


	public function createOrderProcessing(array $data, EntityGenerator $generator): OrderProcessing
	{
		$this->verifyData(['order', 'callback'], $data);
		$order = $generator->maybeCreate(Order::class, $data['order'] ?? true);
		if (isset($data['callback'])) {
			return $data['callback']($data);
		}
		return new TestOrderProcessing($order, TestOrderProcessingStateEnum::CREATED());
	}


	public static function exampleCart(array $additionalData = []): array
	{
		$pricingGroupRef = new EntityReference();
		$countryRef = new EntityReference();
		$variant1Ref = new EntityReference([
			'pricing' => [
				'pricingGroup' => $pricingGroupRef,
			],
		]);
		$variant2Ref = new EntityReference([
			'pricing' => [
				'pricingGroup' => $pricingGroupRef,
			],
		]);
		$data = EntityGenerator::mergeConfig($additionalData, [
			'context' => [
				'channel' => [
					'pricingGroup' => $pricingGroupRef,
				],
			],
			'customer' => [
				'email' => 'frantisek@dobrota.cz',
			],
			'billingInfo' => [
				'address' => [
					'recipientName' => 'Frantisek Dobrota',
					'country' => $countryRef,
				],
			],
			'shippingInfo' => [
				'address' => [
					'recipientName' => 'Frantisek Zlota',
					'country' => $countryRef,
				],
			],
			'productItems' => [
				[
					'variant' => $variant1Ref,
					'quantity' => 1,
				],
				[
					'variant' => $variant2Ref,
					'quantity' => 2,
				],
			],
		]);

		return $data;
	}
}
