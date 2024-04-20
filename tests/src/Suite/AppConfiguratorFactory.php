<?php declare(strict_types = 1);

namespace MangoShopTests\Suite;

use Mangoweb\Tester\Infrastructure\Container\IAppConfiguratorFactory;
use Nette\Configurator;
use Nette\DI\Container;


class AppConfiguratorFactory implements IAppConfiguratorFactory
{

	public function create(Container $testContainer): Configurator
	{
		$params = $testContainer->getParameters();

		$configurator = new Configurator;
		$configurator->setDebugMode(TRUE);
		$configurator->setTempDirectory($params['tempDir']);

		$requiredParams = [
			'logDir',
			'tempDir',
			'rootDir',
			'binDir',
			'srcDir',
			'configDir',
			'wwwDir',
		];

		$configurator->addParameters(array_intersect_key($params, array_fill_keys($requiredParams, TRUE)));
		$configurator->addConfig("$params[configDir]/app.neon");
		$configurator->addConfig("$params[configDir]/local.neon");

		return $configurator;
	}
}
