<?php declare(strict_types = 1);

namespace MangoShop\Order\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IEntityClassMappingProvider;
use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Order\Model\CartProductItemsRepository;
use MangoShop\Order\Model\CartPromotionsRepository;
use MangoShop\Order\Model\CartsRepository;
use MangoShop\Order\Model\CustomersRepository;
use MangoShop\Order\Model\OrderBillingInfoRepository;
use MangoShop\Order\Model\OrderContextsRepository;
use MangoShop\Order\Model\OrderProcessingRepository;
use MangoShop\Order\Model\OrderProductItemsRepository;
use MangoShop\Order\Model\OrderPromotionsRepository;
use MangoShop\Order\Model\OrderService;
use MangoShop\Order\Model\OrderShippingInfoRepository;
use MangoShop\Order\Model\OrdersRepository;
use MangoShop\Order\Model\SessionsRepository;
use MangoShop\Payment\Model\PaymentService;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\Utils\Validators;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class OrderExtension extends CompilerExtension implements IRepositoryClassProvider, IEntityClassMappingProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Validators::assertField($this->config, 'processingEntity', 'string');

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.order.neon')['services'],
			$this->name
		);
	}


	public function beforeCompile()
	{
		parent::beforeCompile();

		$repositoryDefinition = $this->getContainerBuilder()->getDefinitionByType(OrderProcessingRepository::class);
		$repositoryDefinition->addSetup('setEntityClassName', [$this->config['processingEntity']]);

		$paymentService = $this->getContainerBuilder()->getDefinitionByType(PaymentService::class);
		$paymentService->addSetup('registerPaymentStateChangeListener', [new Statement('@' . OrderService::class)]);
	}


	public function getEntityClassNames(): array
	{
		return [
			OrderProcessingRepository::class => [$this->getConfig()['processingEntity']],
		];
	}


	public function getRepositoryClassNames(): array
	{
		return [
			CartsRepository::class,
			CartProductItemsRepository::class,
			CartPromotionsRepository::class,
			CustomersRepository::class,
			OrdersRepository::class,
			OrderBillingInfoRepository::class,
			OrderContextsRepository::class,
			OrderProcessingRepository::class,
			OrderProductItemsRepository::class,
			OrderPromotionsRepository::class,
			OrderShippingInfoRepository::class,
			SessionsRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			(new Group('mangoshop-order-structures', __DIR__ . '/../NextrasMigrations/structures'))
				->setDependencies([
					'mangoshop-address-structures',
					'mangoshop-channel-structures',
					'mangoshop-locale-structures',
					'mangoshop-money-structures',
					'mangoshop-payment-structures',
					'mangoshop-product-structures',
					'mangoshop-promotion-structures',
					'mangoshop-shipping-structures',
				]),
		];
	}
}
