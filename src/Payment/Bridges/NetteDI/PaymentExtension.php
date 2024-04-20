<?php declare(strict_types = 1);

namespace MangoShop\Payment\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IEntityClassMappingProvider;
use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\Payment\Model\PaymentMethodsRepository;
use MangoShop\Payment\Model\PaymentsRepository;
use MangoShop\Payment\Model\PaymentStatesRepository;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;
use Nextras\Migrations\Entities\Group;


class PaymentExtension extends CompilerExtension implements IRepositoryClassProvider, IEntityClassMappingProvider, IMigrationGroupsProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.payment.neon')['services'],
			$this->name
		);
	}


	public function beforeCompile(): void
	{
		$config = $this->validateConfig(['methods' => []]);
		$repositoryDefinition = $this->getContainerBuilder()->getDefinitionByType(PaymentMethodsRepository::class);

		foreach ($config['methods'] as $code => $class) {
			$repositoryDefinition->addSetup('registerPaymentMethod', [$code, $class]);
		}
	}


	public function getEntityClassNames(): array
	{
		$config = $this->validateConfig(['methods' => []]);

		return [
			PaymentMethodsRepository::class => array_values($config['methods']),
		];
	}


	public function getRepositoryClassNames(): array
	{
		return [
			PaymentsRepository::class,
			PaymentMethodsRepository::class,
			PaymentStatesRepository::class,
		];
	}


	public function getMigrationGroups(): array
	{
		return [
			(new Group('mangoshop-payment-structures', __DIR__ . '/../NextrasMigrations/structures'))
				->setDependencies([
					'mangoshop-locale-structures',
					'mangoshop-money-structures',
				]),
		];
	}
}
