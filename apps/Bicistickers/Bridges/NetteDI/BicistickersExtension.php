<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Bridges\NetteDI;

use MangoShop\Bicistickers\Model\DataGenerator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class BicistickersExtension extends CompilerExtension implements IMigrationGroupsProvider
{
	/** @var bool */
	private $debugMode;


	public function __construct(bool $debugMode)
	{
		$this->debugMode = $debugMode;
	}


	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Validators::assertField($this->config, 'mail', 'array');
		Validators::assertField($this->config['mail'], 'fromName', 'string');
		Validators::assertField($this->config['mail'], 'fromEmail', 'email');

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.bicistickers.neon')['services'],
			$this->name
		);

		$builder = $this->getContainerBuilder();
		$builder->getDefinition($this->prefix('orderMailer'))
			->setArguments([
				'fromName' => $this->config['mail']['fromName'],
				'fromEmail' => $this->config['mail']['fromEmail'],
			]);
		if ($this->debugMode) {
			$builder->addDefinition($this->prefix('dataGenerator'))
				->setClass(DataGenerator::class)
				->addTag(BicistickersBootstrapExtension::getTagName());
		}
	}


	public function getMigrationGroups(): array
	{
		return [
			(new Group('bicistickers-structures', __DIR__ . '/../NextrasMigrations/structures'))
				->setDependencies(['mangoshop-order-structures']),
			(new Group('bicistickers-basic-data', __DIR__ . '/../NextrasMigrations/basic-data'))
				->setDependencies(['bicistickers-structures']),
			(new Group('bicistickers-dummy-data', __DIR__ . '/../NextrasMigrations/dummy-data'))
				->setDependencies(['bicistickers-basic-data']),
		];
	}
}
