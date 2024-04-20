<?php declare(strict_types = 1);

namespace MangoShop\Core;

interface IStringWrapper
{
	public function __construct(string $value);

	public function __toString();
}
