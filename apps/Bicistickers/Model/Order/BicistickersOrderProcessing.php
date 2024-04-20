<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

/**
 * @property-read BicistickersOrderProcessingStateEnum $state  {container \MangoShop\Core\NextrasOrm\EnumProperty}
 */
class BicistickersOrderProcessing extends \MangoShop\Order\Model\OrderProcessing
{
	private const STATE_TRANSITIONS = [
		BicistickersOrderProcessingStateEnum::CREATED => [
			BicistickersOrderProcessingStateEnum::GENERATING_PDFS => true,
			BicistickersOrderProcessingStateEnum::POSTPONED => true,
		],
		BicistickersOrderProcessingStateEnum::GENERATING_PDFS => [
			BicistickersOrderProcessingStateEnum::WAITING_TO_PRINT => true,
		],
		BicistickersOrderProcessingStateEnum::WAITING_TO_PRINT => [
			BicistickersOrderProcessingStateEnum::PRINTED => true,
			BicistickersOrderProcessingStateEnum::POSTPONED => true,
		],
		BicistickersOrderProcessingStateEnum::POSTPONED => [
			BicistickersOrderProcessingStateEnum::CREATED => true,
		],
		BicistickersOrderProcessingStateEnum::PRINTED => [
		],
	];


	public function isDispatched(): bool
	{
		return $this->state === BicistickersOrderProcessingStateEnum::PRINTED();
	}


	public function isAllowed(BicistickersOrderProcessingStateEnum $toState): bool
	{
		return isset(self::STATE_TRANSITIONS[$this->state->getValue()][$toState->getValue()]);
	}
}
