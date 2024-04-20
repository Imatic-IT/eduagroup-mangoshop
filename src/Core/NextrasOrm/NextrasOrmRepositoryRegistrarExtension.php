<?php declare(strict_types = 1);

namespace MangoShop\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IEntityClassMappingProvider;
use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Core\NextrasOrm\Repository;
use Nette\DI\CompilerExtension;
use Nextras\Orm\Model\IModel;


class NextrasOrmRepositoryRegistrarExtension extends CompilerExtension
{
	/** @var string */
	private $ormExtensionName;


	public function __construct(string $ormExtensionName = 'nextras.orm')
	{
		$this->ormExtensionName = $ormExtensionName;
	}


	public function loadConfiguration()
	{
		$classes = [];
		foreach ($this->compiler->getExtensions(IRepositoryClassProvider::class) as $extension) {
			assert($extension instanceof IRepositoryClassProvider);
			$classes[] = $extension->getRepositoryClassNames();
		}
		foreach (array_merge(...$classes) as $name => $repositoryClass) {
			if (is_int($name)) {
				$name = str_replace('\\', '_', $repositoryClass);
			}
			$mapperClass = str_replace('Repository', 'Mapper', $repositoryClass);
			$this->setupMapperService($name, $mapperClass);
			$this->setupRepositoryService($name, $repositoryClass);
		}

		foreach ($this->getEntityClassMapping() as $repositoryClass => $entityClassNames) {
			foreach ($entityClassNames as $entityClassName) {
				Repository::addEntityClass($repositoryClass, $entityClassName);
			}
		}
	}


	public function beforeCompile()
	{
		$modelDefinition = $this->getContainerBuilder()->getDefinitionByType(IModel::class);
		foreach ($this->getEntityClassMapping() as $repositoryClass => $entityClassNames) {
			foreach ($entityClassNames as $entityClassName) {
				$modelDefinition->addSetup(sprintf('%s::%s', Repository::class, 'addEntityClass'), [$repositoryClass, $entityClassName]);
			}
		}
	}


	protected function setupMapperService(string $repositoryName, string $mapperClass)
	{
		$mapperName = $this->prefix('mappers.' . $repositoryName);
		$builder = $this->getContainerBuilder();
		if ($builder->hasDefinition($mapperName)) {
			return;
		}

		$builder->addDefinition($mapperName)
			->setClass($mapperClass)
			->setArguments([
				'cache' => "@{$this->ormExtensionName}.cache",
			]);
	}


	protected function setupRepositoryService(string $repositoryName, string $repositoryClass)
	{
		$serviceName = $this->prefix('repositories.' . $repositoryName);
		$builder = $this->getContainerBuilder();
		if ($builder->hasDefinition($serviceName)) {
			return;
		}

		$builder->addDefinition($serviceName)
			->setClass($repositoryClass)
			->setArguments([
				'mapper' => $this->prefix('@mappers.' . $repositoryName),
			]);
	}


	protected function getEntityClassMapping(): array
	{
		$mapping = [];
		foreach ($this->compiler->getExtensions(IEntityClassMappingProvider::class) as $extension) {
			assert($extension instanceof IEntityClassMappingProvider);
			$mapping[] = $extension->getEntityClassNames();
		}

		//merge recursive because multiple extensions may provide mapping for a single repository
		return array_merge_recursive(...$mapping);
	}
}
