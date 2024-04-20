<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Money;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Shipping\Model\ShippingMethod;
use Mangoweb\Clock\Clock;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read null|Cart                     $previousVersion          {m:1 Cart, oneSided=true}
 * @property-read OrderContext                  $context                  {m:1 OrderContext, oneSided=true}
 * @property-read null|Customer                 $customer                 {m:1 Customer, oneSided=true}
 * @property-read null|OrderShippingInfo        $shippingInfo             {m:1 OrderShippingInfo, oneSided=true}
 * @property-read null|ShippingMethod           $shippingMethod           {m:1 ShippingMethod, oneSided=true}
 * @property-read null|OrderBillingInfo         $billingInfo              {m:1 OrderBillingInfo, oneSided=true}
 * @property-read null|PaymentMethod            $paymentMethod            {m:1 PaymentMethod, oneSided=true}
 * @property-read DateTimeImmutable             $createdAt
 * @property-read Money                         $totalPrice               {virtual}
 *
 * @property-read ICollection|CartProductItem[] $productItems             {1:m CartProductItem::$cart}
 * @property-read ICollection|CartProductItem[] $availableProductItems    {virtual}
 * @property-read ICollection|CartPromotion[]   $promotions               {1:m CartPromotion::$cart}
 */
class Cart extends Entity
{
	/**
	 * @param CartProductItemDto[] $productItems
	 * @param CartPromotionDto[]   $promotions
	 */
	public function __construct(
		OrderContext $orderContext,
		?Customer $customer = null,
		?OrderShippingInfo $shippingInfo = null,
		?ShippingMethod $shippingMethod = null,
		?OrderBillingInfo $billingInfo = null,
		?PaymentMethod $paymentMethod = null,
		?self $previousVersion = null,
		array $productItems = [],
		array $promotions = []
	) {
		parent::__construct();
		$this->setReadOnlyValue('previousVersion', $previousVersion);
		$this->setReadOnlyValue('context', $orderContext);
		$this->setReadOnlyValue('createdAt', Clock::now());
		$this->setReadOnlyValue('customer', $customer);
		$this->setReadOnlyValue('shippingInfo', $shippingInfo);
		$this->setReadOnlyValue('shippingMethod', $shippingMethod);
		$this->setReadOnlyValue('billingInfo', $billingInfo);
		$this->setReadOnlyValue('paymentMethod', $paymentMethod);
		foreach ($productItems as $item) {
			$this->addItem($item);
		}
		foreach ($promotions as $promotion) {
			$this->addPromotion($promotion);
		}
	}


	public function withContext(OrderContext $context): self
	{
		return new self(
			$context,
			$this->customer,
			$this->shippingInfo,
			$this->shippingMethod,
			$this->billingInfo,
			$this->paymentMethod,
			$this,
			$this->getProductItemsDto(),
			$this->getPromotionsDto()
		);
	}


	/**
	 * @return CartProductItemDto[]
	 */
	public function getProductItemsDto(): array
	{
		$result = [];
		foreach ($this->productItems as $item) {
			$result[] = new CartProductItemDto($item->productVariant, $item->quantity, $item->productVariantConfiguration);
		}
		return $result;
	}


	/**
	 * @return CartPromotionDto[]
	 */
	public function getPromotionsDto(): array
	{
		$result = [];
		foreach ($this->promotions as $promotion) {
			$coupon = $promotion->promotionCoupon;
			assert($coupon !== null);
			$result[] = new CartPromotionDto($coupon, 1);
		}
		return $result;
	}


	protected function getterTotalPrice(): Money
	{
		$money = new Money(0, $this->context->currency);

		foreach ($this->availableProductItems as $productItem) {
			$money = $money->add($productItem->totalPrice);
		}

		$beforePromotions = $money;
		foreach ($this->promotions as $promotionItem) {
			$money = $money->add($promotionItem->promotion->computeDiscount($beforePromotions));
		}

		return $money;
	}


	private function addItem(CartProductItemDto $item): void
	{
		$productVariant = $item->getVariant();
		$quantity = $item->getQuantity();

		assert($quantity > 0);
		// intentionally not checking availability. it is already checked in a cart update request

		$newProductItem = new CartProductItem($this, $productVariant, $quantity, $item->getConfiguration());
		$this->getRelationship('productItems')->add($newProductItem);
	}


	private function addPromotion(CartPromotionDto $promotion): void
	{
		$promotionCoupon = $promotion->getCoupon();
		$quantity = $promotion->getQuantity();

		assert($promotionCoupon->isValid());
		assert($quantity === 1);

		$newPromotionItem = new CartPromotion($this, $promotionCoupon->promotion, $promotionCoupon);
		$this->getRelationship('promotions')->add($newPromotionItem);
	}


	/**
	 * @return ICollection|CartProductItem[]
	 */
	protected function getterAvailableProductItems(): ICollection
	{
		return $this->productItems->findBy([
			'this->productVariant->product->enabled' => true,
			'this->productVariant->pricings->productPricingGroup' => $this->context->productPricingGroup,
		]);
	}
}
