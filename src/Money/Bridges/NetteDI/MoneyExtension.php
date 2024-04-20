<?php declare(strict_types = 1);

namespace MangoShop\Money\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Money\Model\CurrenciesRepository;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class MoneyExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.money.neon')['services'],
			$this->name
		);
	}


	public function getRepositoryClassNames(): array
	{
		return [
			CurrenciesRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			new Group(
				'mangoshop-money-structures',
				__DIR__ . '/../NextrasMigrations/structures'
			),
		];
	}
}
