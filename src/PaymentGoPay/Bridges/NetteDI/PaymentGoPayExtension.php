<?php declare(strict_types = 1);

namespace MangoShop\PaymentGoPay\Bridges\NetteDI;

use MangoShop\Core\NextrasOrm\IEntityClassMappingProvider;
use MangoShop\Payment\Model\PaymentDriverProvider;
use MangoShop\Payment\Model\PaymentMethodsRepository;
use MangoShop\PaymentGoPay\Model\GoPayPaymentMethod;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;


class PaymentGoPayExtension extends CompilerExtension implements IEntityClassMappingProvider
{
	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		Compiler::loadDefinitions(
			$this->getContainerBuilder(),
			$this->loadFromFile(__DIR__ . '/config.paymentGoPay.neon')['services'],
			$this->name
		);

		Validators::assertField($this->config, 'goid', 'int');
		Validators::assertField($this->config, 'clientId', 'int');
		Validators::assertField($this->config, 'clientSecret', 'string');
		Validators::assertField($this->config, 'isProductionMode', 'bool');

		Validators::assertField($this->config, 'paymentMethodCode', 'string');
		Validators::assertField($this->config, 'returnEndpointUrl', 'url');
		Validators::assertField($this->config, 'notifyEndpointUrl', 'url');
	}


	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$driverDefinition = $this->getContainerBuilder()->getDefinition($this->prefix('paymentDriver'));
		$driverDefinition->setArguments([
			'paymentMethodCode' => $this->config['paymentMethodCode'],
			'returnEndpointUrl' => $this->config['returnEndpointUrl'],
			'notifyEndpointUrl' => $this->config['notifyEndpointUrl'],
		]);

		$goPayApiClientDefinition = $this->getContainerBuilder()->getDefinition($this->prefix('goPayApiClient'));
		$goPayApiClientDefinition->setFactory('GoPay\Api::payments', [
			'config' => [
				'goid' => $this->config['goid'],
				'clientId' => $this->config['clientId'],
				'clientSecret' => $this->config['clientSecret'],
				'isProductionMode' => $this->config['isProductionMode'],
			],
		]);

		$repositoryDefinition = $this->getContainerBuilder()->getDefinitionByType(PaymentMethodsRepository::class);
		$repositoryDefinition->addSetup('registerPaymentMethod', [$this->config['paymentMethodCode'], GoPayPaymentMethod::class]);

		$driverProviderDefinition = $this->getContainerBuilder()->getDefinitionByType(PaymentDriverProvider::class);
		$driverProviderDefinition->addSetup('registerPaymentDriver', [$this->config['paymentMethodCode'], $driverDefinition]);
	}


	public function getEntityClassNames(): array
	{
		return [
			PaymentMethodsRepository::class => [
				GoPayPaymentMethod::class,
			],
		];
	}
}
