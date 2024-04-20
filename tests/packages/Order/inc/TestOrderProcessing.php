<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Inc;

use MangoShop\Order\Model\OrderProcessing;

/**
 * @property-read TestOrderProcessingStateEnum $state {container \MangoShop\Core\NextrasOrm\EnumProperty}
 */
class TestOrderProcessing extends OrderProcessing
{
	public function isDispatched(): bool
	{
		return $this->state === TestOrderProcessingStateEnum::DISPATCHED();
	}


	public function isAllowed(TestOrderProcessingStateEnum $toState): bool
	{
		static $transitions = [
			TestOrderProcessingStateEnum::CREATED => [TestOrderProcessingStateEnum::PACKING => true],
			TestOrderProcessingStateEnum::PACKING => [TestOrderProcessingStateEnum::DISPATCHED => true],
			TestOrderProcessingStateEnum::DISPATCHED => [],
		];

		return isset($transitions[$this->state->getValue()][$toState->getValue()]);
	}
}
