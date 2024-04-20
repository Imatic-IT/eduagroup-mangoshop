<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\NextrasOrm\Entity;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read Country               $country   {m:1 Country::$states}
 * @property-read null|string           $code
 * @property-read string                $name
 *
 * @property-read ICollection|Address[] $addresses {1:m Address::$state}
 */
class CountryState extends Entity
{
	public function __construct(Country $country, ?string $code, string $name)
	{
		parent::__construct();
		$this->setReadOnlyValue('country', $country);
		$this->setReadOnlyValue('code', $code);
		$this->setReadOnlyValue('name', $name);
	}
}
