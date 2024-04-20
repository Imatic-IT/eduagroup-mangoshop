<?php declare(strict_types = 1);

namespace MangoShopTests\Promotion\Inc;

use MangoShop\Promotion\Model\Promotion;
use MangoShop\Promotion\Model\PromotionCoupon;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;
use Mangoweb\Clock\Clock;


class PromotionEntityFactory extends EntityFactory
{
	public function createPromotionCoupon(array $data, EntityGenerator $entityGenerator): PromotionCoupon
	{
		$this->verifyData([], $data);
		$promotion = $entityGenerator->create(Promotion::class, []);

		return new PromotionCoupon($promotion, $this->counter(PromotionCoupon::class, 'CODE'), 1);
	}


	public function createPromotion(array $data): Promotion
	{
		$this->verifyData([], $data);
		return new Promotion('Promotion', 0.1, Clock::now(), Clock::now()->add(new \DateInterval('P10D')));
	}
}
