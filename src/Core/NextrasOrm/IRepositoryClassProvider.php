<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

interface IRepositoryClassProvider
{
	public function getRepositoryClassNames(): array;
}
