<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\NextrasOrm\Entity;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string                     $code      ISO 3166-1 alpha-2
 *
 * @property-read ICollection|Address[]      $addresses {1:m Address::$country}
 * @property-read ICollection|CountryState[] $states    {1:m CountryState::$country}
 */
class Country extends Entity
{
	public function __construct(string $code)
	{
		parent::__construct();
		assert(strlen($code) === 2);
		$this->setReadOnlyValue('code', $code);
	}
}
