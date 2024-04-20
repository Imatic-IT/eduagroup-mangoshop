<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Money;
use MangoShop\Payment\Model\Payment;
use MangoShop\Shipping\Model\ShippingMethod;
use Mangoweb\Clock\Clock;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read Cart                           $cart                   {m:1 Cart, oneSided=true}
 * @property-read OrderContext                   $context                {m:1 OrderContext, oneSided=true}
 * @property-read Customer                       $customer               {m:1 Customer::$orders}
 * @property-read OrderShippingInfo              $shippingInfo           {m:1 OrderShippingInfo, oneSided=true}
 * @property-read ShippingMethod                 $shippingMethod         {m:1 ShippingMethod, oneSided=true}
 * @property-read OrderBillingInfo               $billingInfo            {m:1 OrderBillingInfo, oneSided=true}
 * @property-read Payment                        $payment                {m:1 Payment, oneSided=true}
 * @property-read null|OrderProcessing           $processing             {m:1 OrderProcessing, oneSided=true}
 * @property-read OrderStateEnum                 $state                  {container \MangoShop\Core\NextrasOrm\EnumProperty}
 * @property-read OrderFailureReasonEnum         $failureReason          {container \MangoShop\Core\NextrasOrm\EnumProperty}
 * @property-read DateTimeImmutable              $createdAt
 * @property-read null|DateTimeImmutable         $processingStartedAt
 * @property-read null|DateTimeImmutable         $dispatchedAt
 * @property-read null|DateTimeImmutable         $fulfilledAt
 * @property-read null|DateTimeImmutable         $failedAt
 *
 * @property-read ICollection|OrderProductItem[] $productItems           {1:m OrderProductItem::$order}
 * @property-read ICollection|OrderPromotion[]   $promotions             {1:m OrderPromotion::$order}
 */
class Order extends Entity
{
	public function __construct(Cart $cart, Payment $payment)
	{
		parent::__construct();

		assert($cart->customer !== null);
		assert($cart->shippingInfo !== null);
		assert($cart->shippingMethod !== null);
		assert($cart->billingInfo !== null);
		assert($cart->paymentMethod !== null);

		$this->setReadOnlyValue('cart', $cart);
		$this->setReadOnlyValue('context', $cart->context);
		$this->setReadOnlyValue('customer', $cart->customer);
		$this->setReadOnlyValue('shippingInfo', $cart->shippingInfo);
		$this->setReadOnlyValue('shippingMethod', $cart->shippingMethod);
		$this->setReadOnlyValue('billingInfo', $cart->billingInfo);

		$amount = new Money(0, $this->context->currency);
		$productItemsRelationship = $this->getRelationship('productItems');
		foreach ($cart->availableProductItems as $cartProductItem) {
			$productItem = new OrderProductItem($this, $cartProductItem);
			assert($productItemsRelationship->has($productItem));

			$amount = $amount->add($productItem->totalPrice);
		}

		$promotionsRelationship = $this->getRelationship('promotions');
		$amountBeforePromotions = $amount;
		foreach ($cart->promotions as $cartPromotionItem) {
			$promotionItem = new OrderPromotion($this, $cartPromotionItem, $amountBeforePromotions);
			assert($promotionsRelationship->has($promotionItem));
			assert($promotionItem->priceCents <= 0);

			$amount = $amount->add($promotionItem->price);
		}

		assert($payment->amountCurrency === $amount->getCurrency());
		assert($payment->amountCents === $amount->getCents());
		$this->setReadOnlyValue('payment', $payment);

		$this->setReadOnlyValue('state', OrderStateEnum::WAITING_FOR_PAYMENT());
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	public function startProcessing(OrderProcessing $processing): void
	{
		assert($this->state === OrderStateEnum::WAITING_FOR_PAYMENT());
		assert($this->processingStartedAt === null);
		assert($this->dispatchedAt === null);
		assert($this->fulfilledAt === null);
		assert($this->payment->isApproved());

		$this->setReadOnlyValue('state', OrderStateEnum::PROCESSING());
		$this->setReadOnlyValue('processingStartedAt', Clock::now());
		$this->setReadOnlyValue('processing', $processing);
	}


	public function advanceProcessing(OrderProcessing $processing): void
	{
		assert($this->state === OrderStateEnum::PROCESSING());
		assert($this->processing !== null);
		assert($processing->previousVersion === $this->processing);
		$this->setReadOnlyValue('processing', $processing);
	}


	public function markDispatched(): void
	{
		assert($this->state === OrderStateEnum::PROCESSING());
		assert($this->processingStartedAt !== null);
		assert($this->dispatchedAt === null);
		assert($this->fulfilledAt === null);
		assert($this->payment->isApproved());

		$this->setReadOnlyValue('state', OrderStateEnum::DISPATCHED());
		$this->setReadOnlyValue('dispatchedAt', Clock::now());
	}


	public function markFulfilled(): void
	{
		assert($this->state === OrderStateEnum::DISPATCHED());
		assert($this->processingStartedAt !== null);
		assert($this->dispatchedAt !== null);
		assert($this->fulfilledAt === null);
		assert($this->payment->isApproved());

		$this->setReadOnlyValue('state', OrderStateEnum::FULFILLED());
		$this->setReadOnlyValue('fulfilledAt', Clock::now());
	}


	public function markFailed(OrderFailureReasonEnum $reason): void
	{
		assert($this->state !== OrderStateEnum::FAILED());

		$this->setReadOnlyValue('state', OrderStateEnum::FAILED());
		$this->setReadOnlyValue('failedAt', Clock::now());
		$this->setReadOnlyValue('failureReason', $reason);
	}
}
