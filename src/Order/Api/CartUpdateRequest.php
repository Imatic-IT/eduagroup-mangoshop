<?php declare(strict_types = 1);

namespace MangoShop\Order\Api;

use MangoShop\Order\Model\Cart;
use MangoShop\Order\Model\CartProductItemDto;
use MangoShop\Order\Model\CartPromotionDto;
use MangoShop\Order\Model\Customer;
use MangoShop\Order\Model\OrderBillingInfo;
use MangoShop\Order\Model\OrderContext;
use MangoShop\Order\Model\OrderShippingInfo;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Product\Model\ProductVariant;
use MangoShop\Promotion\Model\PromotionCoupon;
use MangoShop\Shipping\Model\ShippingMethod;

class CartUpdateRequest
{
	/** @var OrderContext */
	private $context;

	/** @var null|Customer */
	private $customer;

	/** @var null|OrderShippingInfo */
	private $shippingInfo;

	/** @var null|ShippingMethod */
	private $shippingMethod;

	/** @var null|OrderBillingInfo */
	private $billingInfo;

	/** @var null|PaymentMethod */
	private $paymentMethod;

	/** @var CartProductItemDto[] */
	private $productItems = [];

	/** @var CartPromotionDto[] */
	private $promotions = [];

	/** @var Cart */
	private $previousCart;

	/** @var bool */
	private $updated = false;


	public function __construct(Cart $cart)
	{
		$this->previousCart = $cart;
		$this->context = $cart->context;
		$this->customer = $cart->customer;
		$this->shippingInfo = $cart->shippingInfo;
		$this->shippingMethod = $cart->shippingMethod;
		$this->billingInfo = $cart->billingInfo;
		$this->paymentMethod = $cart->paymentMethod;
		$this->productItems = $cart->getProductItemsDto();
		$this->promotions = $cart->getPromotionsDto();
	}


	public function isUpdated(): bool
	{
		return $this->updated;
	}


	public function getContext(): OrderContext
	{
		return $this->context;
	}


	public function getPreviousCart(): Cart
	{
		return $this->previousCart;
	}


	public function getCustomer(): ?Customer
	{
		return $this->customer;
	}


	public function withCustomer(?Customer $customer): self
	{
		$builder = clone $this;
		$builder->customer = $customer;
		return $builder;
	}


	public function getShippingInfo(): ?OrderShippingInfo
	{
		return $this->shippingInfo;
	}


	public function withShippingInfo(?OrderShippingInfo $shippingInfo): self
	{
		$builder = clone $this;
		$builder->shippingInfo = $shippingInfo;
		return $builder;
	}


	public function getShippingMethod(): ?ShippingMethod
	{
		return $this->shippingMethod;
	}


	public function withShippingMethod(?ShippingMethod $shippingMethod): self
	{
		$builder = clone $this;
		$builder->shippingMethod = $shippingMethod;
		return $builder;
	}


	public function getBillingInfo(): ?OrderBillingInfo
	{
		return $this->billingInfo;
	}


	public function withBillingInfo(?OrderBillingInfo $billingInfo): self
	{
		$builder = clone $this;
		$builder->billingInfo = $billingInfo;
		return $builder;
	}


	public function getPaymentMethod(): ?PaymentMethod
	{
		return $this->paymentMethod;
	}


	public function withPaymentMethod(?PaymentMethod $paymentMethod): self
	{
		$builder = clone $this;
		$builder->paymentMethod = $paymentMethod;
		return $builder;
	}


	/**
	 * @return CartProductItemDto[]
	 */
	public function getProductItems(): array
	{
		return $this->productItems;
	}


	public function withProductItem(ProductVariant $variant, int $quantity, array $configuration = []): self
	{
		assert($variant->product->enabled);
		assert($variant->getPricingIn($this->context->channel->pricingGroup) !== null);

		$item = new CartProductItemDto($variant, $quantity, $configuration);
		$builder = clone $this->withoutProductItem($item);
		if ($item->getQuantity() > 0) {
			$builder->productItems[] = $item;
		}
		return $builder;
	}


	public function withoutProductItems(): self
	{
		$builder = clone $this;
		$builder->productItems = [];
		return $builder;
	}


	private function withoutProductItem(CartProductItemDto $removedItem): self
	{
		$builder = clone $this;
		$items = [];
		foreach ($builder->productItems as $item) {
			if (!$item->equalsIgnoringQuantity($removedItem)) {
				$items[] = $item;
			}
		}
		$builder->productItems = $items;
		return $builder;
	}


	/**
	 * @return CartPromotionDto[]
	 */
	public function getPromotions(): array
	{
		return $this->promotions;
	}


	public function withPromotionCoupon(PromotionCoupon $coupon, int $quantity = 1): self
	{
		$promotion = new CartPromotionDto($coupon, $quantity);
		$builder = clone $this->withoutPromotionCoupon($promotion);
		if ($promotion->getQuantity() > 0) {
			$builder->promotions[] = $promotion;
		}
		return $builder;
	}


	public function withoutPromotionCoupons(): self
	{
		$builder = clone $this;
		$builder->promotions = [];
		return $builder;
	}


	private function withoutPromotionCoupon(CartPromotionDto $removedPromotion): self
	{
		$builder = clone $this;
		$promotions = [];
		foreach ($builder->promotions as $promotion) {
			if (!$promotion->equalsIgnoringQuantity($removedPromotion)) {
				$promotions[] = $removedPromotion;
			}
		}
		$builder->promotions = $promotions;
		return $builder;
	}


	public function __clone()
	{
		$this->updated = true;
	}
}
