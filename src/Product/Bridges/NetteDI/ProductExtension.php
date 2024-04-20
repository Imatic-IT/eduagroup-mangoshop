<?php declare(strict_types = 1);

namespace MangoShop\Product\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Product\Model\ProductPricingGroupsRepository;
use MangoShop\Product\Model\ProductsRepository;
use MangoShop\Product\Model\ProductTranslationsRepository;
use MangoShop\Product\Model\ProductVariantPricingsRepository;
use MangoShop\Product\Model\ProductVariantsRepository;
use MangoShop\Product\Model\ProductVariantTranslationsRepository;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class ProductExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.product.neon')['services'],
			$this->name
		);
	}


	public function getRepositoryClassNames(): array
	{
		return [
			ProductsRepository::class,
			ProductPricingGroupsRepository::class,
			ProductTranslationsRepository::class,
			ProductVariantsRepository::class,
			ProductVariantPricingsRepository::class,
			ProductVariantTranslationsRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			(new Group('mangoshop-product-structures', __DIR__ . '/../NextrasMigrations/structures'))
				->setDependencies([
					'mangoshop-locale-structures',
					'mangoshop-money-structures',
				]),
		];
	}
}
