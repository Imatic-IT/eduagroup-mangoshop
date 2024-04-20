<?php declare(strict_types = 1);

namespace MangoShop\Shipping\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Shipping\Model\ShippingMethodsRepository;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class ShippingExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function getRepositoryClassNames(): array
	{
		return [
			ShippingMethodsRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			new Group(
				'mangoshop-shipping-structures',
				__DIR__ . '/../NextrasMigrations/structures'
			),
		];
	}
}
