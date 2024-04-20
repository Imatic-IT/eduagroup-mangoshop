<?php declare(strict_types = 1);

namespace MangoShop\Locale\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Locale\Model\LocalesRepository;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class LocaleExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.locale.neon')['services'],
			$this->name
		);
	}


	public function getRepositoryClassNames(): array
	{
		return [
			LocalesRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			new Group(
				'mangoshop-locale-structures',
				__DIR__ . '/../NextrasMigrations/structures'
			),
		];
	}
}
