<?php declare(strict_types = 1);

namespace MangoShop\Money\Model;

use MangoShop\Core\NextrasOrm\Entity;


/**
 * @property-read string $code ISO 4217
 */
class Currency extends Entity
{
	public function __construct(string $code)
	{
		assert(strlen($code) === 3);
		assert(ctype_upper($code));

		parent::__construct();
		$this->setReadOnlyValue('code', $code);
	}
}
