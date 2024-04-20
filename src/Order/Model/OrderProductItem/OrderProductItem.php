<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Money;
use MangoShop\Product\Model\ProductVariant;


/**
 * @property-read Order          $order                       {m:1 Order::$productItems}
 * @property-read ProductVariant $productVariant              {m:1 ProductVariant, oneSided=true}
 * @property-read array          $productVariantConfiguration {container \MangoShop\Core\NextrasOrm\JsonProperty}
 * @property-read int            $quantity
 * @property-read int            $unitPriceCents
 * @property-read Money          $unitPrice                   {virtual}
 * @property-read Money          $totalPrice                  {virtual}
 */
class OrderProductItem extends Entity
{
	public function __construct(Order $order, CartProductItem $cartProductItem)
	{
		parent::__construct();

		$pricingGroup = $order->context->channel->pricingGroup;
		$productVariant = $cartProductItem->productVariant;
		$pricing = $pricingGroup->getPricingFor($productVariant);
		assert($pricing !== null);

		$unitPriceCents = $pricing->priceCents;

		$this->setReadOnlyValue('order', $order);
		$this->setReadOnlyValue('productVariant', $cartProductItem->productVariant);
		$this->setReadOnlyValue('quantity', $cartProductItem->quantity);
		$this->setReadOnlyValue('unitPriceCents', $unitPriceCents);
	}


	public function getterUnitPrice(): Money
	{
		return new Money($this->unitPriceCents, $this->order->context->currency);
	}


	public function getterTotalPrice(): Money
	{
		return $this->unitPrice->multiply($this->quantity);
	}
}
