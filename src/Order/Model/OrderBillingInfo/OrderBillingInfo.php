<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Address\Model\Address;
use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read Address     $address {m:1 Address, oneSided=true}
 * @property-read null|string $vatIdentifier
 * @property-read null|string $companyIdentifier
 */
class OrderBillingInfo extends Entity
{
	public function __construct(Address $address, ?string $vatIdentifier, ?string $companyIdentifier)
	{
		parent::__construct();

		$this->setReadOnlyValue('address', $address);
		$this->setReadOnlyValue('vatIdentifier', $vatIdentifier);
		$this->setReadOnlyValue('companyIdentifier', $companyIdentifier);
	}


	public function withAddress(Address $address): self
	{
		if ($address === $this->address) {
			return $this;
		}

		return new self($address, $this->vatIdentifier, $this->companyIdentifier);
	}


	public function withVatIdentifier(?string $vatIdentifier): self
	{
		if ($vatIdentifier === $this->vatIdentifier) {
			return $this;
		}

		return new self($this->address, $vatIdentifier, $this->companyIdentifier);
	}


	public function withCompanyIdentifier(?string $companyIdentifier): self
	{
		if ($companyIdentifier === $this->companyIdentifier) {
			return $this;
		}

		return new self($this->address, $this->vatIdentifier, $companyIdentifier);
	}
}
