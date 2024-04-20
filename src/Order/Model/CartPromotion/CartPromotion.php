<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Promotion\Model\Promotion;
use MangoShop\Promotion\Model\PromotionCoupon;


/**
 * @property-read Cart                 $cart            {m:1 Cart::$promotions}
 * @property-read Promotion            $promotion       {m:1 Promotion, oneSided=true}
 * @property-read null|PromotionCoupon $promotionCoupon {m:1 PromotionCoupon, oneSided=true}
 */
class CartPromotion extends Entity
{
	public function __construct(Cart $cart, Promotion $promotion, ?PromotionCoupon $promotionCoupon)
	{
		parent::__construct();

		assert($promotion->isActive());
		assert($promotionCoupon === null || $promotionCoupon->promotion === $promotion);
		assert($promotionCoupon === null || $promotionCoupon->isValid());

		$this->setReadOnlyValue('cart', $cart);
		$this->setReadOnlyValue('promotion', $promotion);
		$this->setReadOnlyValue('promotionCoupon', $promotionCoupon);
	}
}
