<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\Payment\Model\PaymentOrderItem;
use MangoShop\Payment\Model\PaymentOrderItemTypeEnum;


class PaymentInitializationRequestFactory
{
	public function create(Order $order): PaymentInitializationRequest
	{
		$orderItems = [];
		foreach ($order->productItems as $productItem) {
			$orderItems[] = $this->convertProductItem($productItem);
		}

		foreach ($order->promotions as $promotionItem) {
			$orderItems[] = $this->convertPromotionItem($promotionItem);
		}

		return new PaymentInitializationRequest(
			(string) $order->customer->email,
			(string) $order->id,
			$orderItems
		);
	}


	private function convertProductItem(OrderProductItem $productItem): PaymentOrderItem
	{
		return new PaymentOrderItem(
			PaymentOrderItemTypeEnum::PRODUCT(),
			null,
			null,
			$productItem->quantity,
			sprintf('Product #%d', $productItem->productVariant->id), // TODO: use real translations
			$productItem->totalPrice->getCents()
		);
	}


	private function convertPromotionItem(OrderPromotion $promotion): PaymentOrderItem
	{
		return new PaymentOrderItem(
			PaymentOrderItemTypeEnum::PROMOTION(),
			null,
			null,
			1,
			sprintf('Promotion #%d', $promotion->promotion->id), // TODO: use real translations
			$promotion->priceCents
		);
	}
}
