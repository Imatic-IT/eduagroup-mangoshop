<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Address\Model\Address;
use MangoShop\Address\Model\Phone;
use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read Address    $address {m:1 Address, oneSided=true}
 * @property-read null|Phone $phone   may be empty {container \MangoShop\Core\NextrasOrm\StringWrapperProperty}
 */
class OrderShippingInfo extends Entity
{
	public function __construct(Address $address, ?Phone $phone)
	{
		parent::__construct();

		$this->setReadOnlyValue('address', $address);
		$this->setReadOnlyValue('phone', $phone);
	}


	public function withAddress(Address $address): self
	{
		if ($address === $this->address) {
			return $this;
		}

		return new self($address, $this->phone);
	}


	public function withPhone(?Phone $phone): self
	{
		if ($phone === $this->phone) {
			return $this;
		}

		return new self($this->address, $phone);
	}
}
