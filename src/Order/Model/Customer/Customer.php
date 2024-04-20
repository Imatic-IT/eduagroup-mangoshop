<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Address\Model\Email;
use MangoShop\Core\NextrasOrm\Entity;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read Email               $email  {container \MangoShop\Core\NextrasOrm\StringWrapperProperty}
 *
 * @property-read ICollection|Order[] $orders {1:m Order::$customer}
 */
class Customer extends Entity
{
	public function __construct(Email $email)
	{
		parent::__construct();
		$this->setReadOnlyValue('email', $email);
	}
}
