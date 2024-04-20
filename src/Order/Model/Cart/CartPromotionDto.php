<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Promotion\Model\PromotionCoupon;

class CartPromotionDto
{
	/** @var PromotionCoupon */
	private $coupon;

	/** @var int */
	private $quantity;


	public function __construct(PromotionCoupon $coupon, int $quantity)
	{
		$this->coupon = $coupon;
		$this->quantity = $quantity;
	}


	public function getCoupon(): PromotionCoupon
	{
		return $this->coupon;
	}


	public function getQuantity(): int
	{
		return $this->quantity;
	}


	public function equalsIgnoringQuantity(self $other): bool
	{
		return $this->coupon === $other->getCoupon();
	}
}
