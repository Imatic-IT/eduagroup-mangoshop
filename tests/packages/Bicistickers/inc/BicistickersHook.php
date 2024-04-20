<?php declare(strict_types = 1);

namespace MangoShopTests\Bicistickers\Inc;

use Mangoweb\Tester\Infrastructure\Container\IAppContainerHook;
use Nette\Configurator;
use Nette\DI\Container;
use Nette\DI\ContainerBuilder;

class BicistickersHook implements IAppContainerHook
{
	public function onConfigure(Configurator $configurator): void
	{
		$configurator->addConfig(__DIR__ . '/bicistickers.app-config.neon');
	}


	public function onCompile(ContainerBuilder $builder): void
	{
	}


	public function onCreate(Container $applicationContainer): void
	{
	}
}
