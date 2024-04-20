<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Shipping\Model\ShippingMethod;


/**
 * @property-read CheckoutOptionGroup $checkoutOptionGroup {m:1 CheckoutOptionGroup::$options}
 * @property-read ShippingMethod      $shippingMethod      {m:1 ShippingMethod, oneSided=true}
 * @property-read PaymentMethod       $paymentMethod       {m:1 PaymentMethod, oneSided=true}
 */
class CheckoutOption extends Entity
{
	public function __construct(
		CheckoutOptionGroup $checkoutOptionGroup,
		ShippingMethod $shippingMethod,
		PaymentMethod $paymentMethod
	) {
		parent::__construct();
		$this->setReadOnlyValue('checkoutOptionGroup', $checkoutOptionGroup);
		$this->setReadOnlyValue('shippingMethod', $shippingMethod);
		$this->setReadOnlyValue('paymentMethod', $paymentMethod);
	}
}
