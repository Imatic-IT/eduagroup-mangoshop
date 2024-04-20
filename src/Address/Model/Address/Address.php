<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read string            $recipientName
 * @property-read string            $line1
 * @property-read string            $line2
 * @property-read string            $city
 * @property-read string            $postalCode
 * @property-read null|CountryState $state   {m:1 CountryState::$addresses}
 * @property-read Country           $country {m:1 Country::$addresses}
 */
class Address extends Entity
{
	public function __construct(
		string $recipientName,
		string $line1,
		string $line2,
		string $city,
		string $postalCode,
		?CountryState $state,
		Country $country
	) {
		parent::__construct();

		assert($state === null || $state->country === $country);

		$this->setReadOnlyValue('recipientName', $recipientName);
		$this->setReadOnlyValue('line1', $line1);
		$this->setReadOnlyValue('line2', $line2);
		$this->setReadOnlyValue('city', $city);
		$this->setReadOnlyValue('postalCode', $postalCode);
		$this->setReadOnlyValue('state', $state);
		$this->setReadOnlyValue('country', $country);
	}
}
