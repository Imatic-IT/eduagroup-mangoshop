<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use DateTimeImmutable;
use MangoShop\Core\NextrasOrm\Entity;
use Mangoweb\Clock\Clock;


/**
 * @property-read string            $token
 * @property-read null|Cart         $cart {m:1 Cart, oneSided=true}
 * @property-read DateTimeImmutable $createdAt
 */
class Session extends Entity
{
	public function __construct()
	{
		parent::__construct();
		$this->setReadOnlyValue('token', bin2hex(random_bytes(20)));
		$this->setReadOnlyValue('createdAt', Clock::now());
	}


	public function setCart(Cart $cart): void
	{
		assert($cart->context->session === $this);
		$this->setReadOnlyValue('cart', $cart);
	}
}
