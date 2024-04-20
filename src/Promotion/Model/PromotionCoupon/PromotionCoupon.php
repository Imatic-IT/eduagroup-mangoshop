<?php declare(strict_types = 1);

namespace MangoShop\Promotion\Model;

use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read Promotion $promotion {m:1 Promotion::$coupons}
 * @property-read string    $code
 * @property-read null|int  $usageLimit
 * @property-read int       $usedCount
 */
class PromotionCoupon extends Entity
{
	public function __construct(Promotion $promotion, string $code, ?int $usageLimit)
	{
		parent::__construct();
		$this->setReadOnlyValue('promotion', $promotion);
		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('usageLimit', $usageLimit);
		$this->setReadOnlyValue('usedCount', 0);
	}


	public function isValid(): bool
	{
		$promotionRestrictionOk = $this->promotion->isActive();
		$usageLimitRestrictionOk = $this->usageLimit === null || $this->usedCount < $this->usageLimit;

		return $promotionRestrictionOk && $usageLimitRestrictionOk;
	}


	public function increaseUsageCounter(): void
	{
		assert($this->isValid());
		$this->setReadOnlyValue('usedCount', $this->usedCount + 1);
	}
}
