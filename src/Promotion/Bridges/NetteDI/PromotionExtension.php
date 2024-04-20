<?php declare(strict_types = 1);

namespace MangoShop\Promotion\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Promotion\Model\PromotionCouponsRepository;
use MangoShop\Promotion\Model\PromotionsRepository;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class PromotionExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function getRepositoryClassNames(): array
	{
		return [
			PromotionsRepository::class,
			PromotionCouponsRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			new Group(
				'mangoshop-promotion-structures',
				__DIR__ . '/../NextrasMigrations/structures'
			),
		];
	}
}
