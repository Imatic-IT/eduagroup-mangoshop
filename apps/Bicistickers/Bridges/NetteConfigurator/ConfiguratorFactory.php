<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Bridges\NetteConfigurator;

use Nette\Configurator;

class ConfiguratorFactory
{
	public static function create(string $appDir, string $logDir, string $tempDir): Configurator
	{
		$root = dirname(__DIR__, 4);
		$params = [
			'rootDir' => $root,
			'appDir' => $appDir,
			'logDir' => $logDir,
			'tempDir' => $tempDir,
			'env' => getenv(),
		];

		$configurator = new Configurator;

		$debug = $_SERVER['DEBUG'] ?? $_ENV['DEBUG'] ?? null;

		if ($debug === 'true') {
			$configurator->setDebugMode(true);

		} else {
			$configurator->setDebugMode([
				'qMLw6AC8xuT7T2@193.86.64.162', // mangoweb office
			]);
		}

		$configurator->addParameters($params);
		$configurator->enableDebugger($params['logDir']);

		$configurator->setTempDirectory($params['tempDir']);

		if ($configurator->isDebugMode()) {
			$configurator->createRobotLoader()
				->addDirectory($appDir)
				->addDirectory("$root/packages")
				->addDirectory("$root/src")
				->register();
		}

		return $configurator;
	}
}
