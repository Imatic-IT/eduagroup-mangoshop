<?php declare(strict_types = 1);

namespace MangoShop\Address\Model;

use MangoShop\Core\IStringWrapper;
use Nette\Utils\Validators;

class Email implements IStringWrapper
{
	/** @var string */
	private $email;


	public function __construct(string $email)
	{
		assert(Validators::isEmail($email));
		$this->email = $email;
	}


	public function __toString()
	{
		return $this->email;
	}
}
