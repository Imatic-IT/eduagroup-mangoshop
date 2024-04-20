<?php declare(strict_types = 1);

namespace MangoShop\Channel\Bridges\NetteDI;

use MangoShop\Channel\Model\ChannelsRepository;
use MangoShop\Channel\Model\CheckoutOptionGroupRepository;
use MangoShop\Channel\Model\CheckoutOptionsRepository;
use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class ChannelExtension extends CompilerExtension implements IRepositoryClassProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.channel.neon')['services'],
			$this->name
		);
	}


	public function getRepositoryClassNames(): array
	{
		return [
			ChannelsRepository::class,
			CheckoutOptionsRepository::class,
			CheckoutOptionGroupRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			(new Group('mangoshop-channel-structures', __DIR__ . '/../NextrasMigrations/structures'))
				->setDependencies([
					'mangoshop-locale-structures',
					'mangoshop-money-structures',
					'mangoshop-payment-structures',
					'mangoshop-product-structures',
					'mangoshop-shipping-structures',
				]),
		];
	}
}
