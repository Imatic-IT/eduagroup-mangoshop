<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Money\Model\Currency;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string                       $name
 * @property-read Currency                     $currency {m:1 Currency, oneSided=true}
 *
 * @property-read ICollection|CheckoutOption[] $options  {1:m CheckoutOption::$checkoutOptionGroup}
 */
class CheckoutOptionGroup extends Entity
{
	public function __construct(string $name, Currency $currency)
	{
		parent::__construct();
		$this->setReadOnlyValue('name', $name);
		$this->setReadOnlyValue('currency', $currency);
	}
}
