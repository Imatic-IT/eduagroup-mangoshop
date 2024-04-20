<?php declare(strict_types = 1);

namespace MangoShop\Locale\Model;

use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read string $code
 * @property-read string $languageCode {virtual}
 */
class Locale extends Entity
{
	public function __construct(string $code)
	{
		assert(preg_match('~^[a-z]{2}(?:_[A-Z]{2})?\z~', $code));

		parent::__construct();
		$this->setReadOnlyValue('code', $code);
	}


	protected function getterLanguageCode(): string
	{
		return substr($this->code, 0, 2);
	}
}
