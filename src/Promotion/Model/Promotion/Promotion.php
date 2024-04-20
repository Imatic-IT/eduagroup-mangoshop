<?php declare(strict_types = 1);

namespace MangoShop\Promotion\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Money;
use Mangoweb\Clock\Clock;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string                        $name
 * @property-read float                         $percentDiscount
 * @property-read null|DateTimeImmutable        $startsAt
 * @property-read null|DateTimeImmutable        $endsAt
 * @property-read DateTimeImmutable             $createdAt
 *
 * @property-read ICollection|PromotionCoupon[] $coupons {1:m PromotionCoupon::$promotion}
 */
class Promotion extends Entity
{
	public function __construct(
		string $name,
		float $percentDiscount,
		?DateTimeImmutable $startsAt,
		?DateTimeImmutable $endsAt
	) {
		parent::__construct();

		assert($percentDiscount >= 0.0 && $percentDiscount <= 1.0);

		$this->setReadOnlyValue('name', $name);
		$this->setReadOnlyValue('percentDiscount', $percentDiscount);
		$this->setReadOnlyValue('startsAt', $startsAt);
		$this->setReadOnlyValue('endsAt', $endsAt);
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	public function isActive(): bool
	{
		$now = Clock::now();
		$startsAtRestrictionOk = $this->startsAt === null || $this->startsAt <= $now;
		$endsAtRestrictionOk = $this->endsAt === null || $this->endsAt >= $now;

		return $startsAtRestrictionOk && $endsAtRestrictionOk;
	}


	public function computeDiscount(Money $price): Money
	{
		return $price->percentMultiply($this->percentDiscount)->toNegative();
	}


	public function changeDateRestriction(?DateTimeImmutable $startsAt, ?DateTimeImmutable $endsAt): void
	{
		$this->setReadOnlyValue('startsAt', $startsAt);
		$this->setReadOnlyValue('endsAt', $endsAt);
	}
}
