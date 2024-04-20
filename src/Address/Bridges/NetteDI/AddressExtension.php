<?php declare(strict_types = 1);

namespace MangoShop\Address\Bridges\NetteDI;

use MangoShop\Address\Model\AddressesRepository;
use MangoShop\Address\Model\CountriesRepository;
use MangoShop\Address\Model\CountryStatesRepository;
use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class AddressExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.address.neon')['services'],
			$this->name
		);
	}


	public function getRepositoryClassNames(): array
	{
		return [
			AddressesRepository::class,
			CountriesRepository::class,
			CountryStatesRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			new Group('mangoshop-address-structures', __DIR__ . '/../NextrasMigrations/structures'),
		];
	}
}
