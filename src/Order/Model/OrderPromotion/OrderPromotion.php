<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Money;
use MangoShop\Promotion\Model\Promotion;
use MangoShop\Promotion\Model\PromotionCoupon;


/**
 * @property-read Order                $order           {m:1 Order::$promotions}
 * @property-read Promotion            $promotion       {m:1 Promotion, oneSided=true}
 * @property-read null|PromotionCoupon $promotionCoupon {m:1 PromotionCoupon, oneSided=true}
 * @property-read int                  $priceCents
 * @property-read Money                $price           {virtual}
 */
class OrderPromotion extends Entity
{
	public function __construct(Order $order, CartPromotion $cartPromotionItem, Money $beforePromotion)
	{
		parent::__construct();

		$promotion = $cartPromotionItem->promotion;
		$promotionCoupon = $cartPromotionItem->promotionCoupon;

		assert($promotion->isActive());
		assert($promotionCoupon === null || $promotionCoupon->promotion === $promotion);
		assert($promotionCoupon === null || $promotionCoupon->isValid());

		if ($promotionCoupon !== null) {
			$promotionCoupon->increaseUsageCounter();
		}

		$this->setReadOnlyValue('order', $order);
		$this->setReadOnlyValue('promotion', $promotion);
		$this->setReadOnlyValue('promotionCoupon', $promotionCoupon);
		$this->setReadOnlyValue('priceCents', $this->promotion->computeDiscount($beforePromotion)->getCents());
	}


	protected function getterPrice(): Money
	{
		return new Money($this->priceCents, $this->order->context->currency);
	}
}
