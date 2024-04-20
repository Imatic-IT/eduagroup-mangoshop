<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Bridges\NetteDI;

use Mangoweb\NetteScopeExtension\ScopeExtension;
use Nette;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Bridges\NetteDI\MigrationsExtension;


class BicistickersBootstrapExtension extends ScopeExtension implements IMigrationGroupsProvider
{
	public static function getTagName(): string
	{
		return 'shop.api';
	}


	protected function createInnerConfigurator(): Nette\Configurator
	{
		$configurator = parent::createInnerConfigurator();
		$configurator->addConfig(__DIR__ . '/config.bootstrap-bicistickers.neon');

		return $configurator;
	}


	public function getMigrationGroups(): array
	{
		$groups = [];
		$innerContainer = $this->createInnerConfigurator()->createContainer();
		foreach ($innerContainer->findByTag(MigrationsExtension::TAG_GROUP) as $serviceName => $_) {
			$groups[] = $innerContainer->getService($serviceName);
		}

		return $groups;
	}
}
