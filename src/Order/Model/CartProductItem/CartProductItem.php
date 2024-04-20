<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Money;
use MangoShop\Product\Model\ProductVariant;


/**
 * @property-read Cart           $cart                         {m:1 Cart::$productItems}
 * @property-read ProductVariant $productVariant               {m:1 ProductVariant, oneSided=true}
 * @property-read array          $productVariantConfiguration  {container \MangoShop\Core\NextrasOrm\JsonProperty}
 * @property-read int            $quantity
 * @property-read Money          $unitPrice                    {virtual}
 * @property-read Money          $totalPrice                   {virtual}
 */
class CartProductItem extends Entity
{
	public function __construct(
		Cart $cart,
		ProductVariant $productVariant,
		int $quantity,
		array $configuration = []
	) {
		parent::__construct();

		assert($quantity > 0);

		$this->setReadOnlyValue('cart', $cart);
		$this->setReadOnlyValue('productVariant', $productVariant);
		$this->setReadOnlyValue('quantity', $quantity);
		$this->setReadOnlyValue('productVariantConfiguration', $configuration);
	}


	protected function getterUnitPrice(): Money
	{
		$pricingGroup = $this->cart->context->productPricingGroup;
		$productVariantPricing = $pricingGroup->getPricingFor($this->productVariant);
		assert($productVariantPricing !== null);

		return $productVariantPricing->price;
	}


	protected function getterTotalPrice(): Money
	{
		return $this->unitPrice->multiply($this->quantity);
	}
}
