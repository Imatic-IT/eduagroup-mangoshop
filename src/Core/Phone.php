<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\IStringWrapper;

class Phone implements IStringWrapper
{
	/** @var string */
	private $phone;


	public function __construct(string $phone)
	{
		assert(preg_match('~^\+\d{5,}\z~', $phone));
		$this->phone = $phone;
	}


	public function __toString()
	{
		return $this->phone;
	}
}
