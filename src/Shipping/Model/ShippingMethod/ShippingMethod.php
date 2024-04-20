<?php declare(strict_types = 1);

namespace MangoShop\Shipping\Model;

use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read string $code
 * @property-read bool   $enabled
 */
class ShippingMethod extends Entity
{
	public function __construct(string $code)
	{
		parent::__construct();
		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('enabled', true);
	}


	public function setEnabled(bool $enabled): void
	{
		$this->setReadOnlyValue('enabled', $enabled);
	}
}
