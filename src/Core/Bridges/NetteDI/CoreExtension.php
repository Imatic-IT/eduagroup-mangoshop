<?php declare(strict_types = 1);

namespace MangoShop\Core\Bridges\NetteDI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;


class CoreExtension extends CompilerExtension
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.core.neon')['services'],
			$this->name
		);

		$this->checkRequiredPhpIniOptions();
	}


	private function checkRequiredPhpIniOptions(): void
	{
		foreach (['zend.assertions', 'assert.active', 'assert.exception'] as $mustBeEnabledOption) {
			if (ini_get($mustBeEnabledOption) !== '1' && ini_set($mustBeEnabledOption, '1') === false) {
				throw new \RuntimeException("php.ini option $mustBeEnabledOption MUST be set to 1, actual is " . var_export(ini_get($mustBeEnabledOption), true));
			}
		}
	}
}
