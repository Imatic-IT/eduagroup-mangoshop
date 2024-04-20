<?php declare(strict_types = 1);

namespace MangoShopTests;

use MangoShop;
use MangoShop\Core\NextrasOrm\IEntityClassMappingProvider;
use MangoShop\Core\NextrasOrm\IRepositoryClassProvider;
use MangoShop\PaymentGoPay\Model\GoPayPaymentMethod;
use MangoShopTests\Order\Inc\TestOrderProcessing;
use MangoShopTests\Payment\Inc\DummyPaymentMethod;
use Nette\DI\CompilerExtension;
use Nextras\Migrations\Bridges\NetteDI\IMigrationGroupsProvider;


class InfrastructureExtension extends CompilerExtension implements IRepositoryClassProvider, IEntityClassMappingProvider, IMigrationGroupsProvider
{
	public function getEntityClassNames(): array
	{
		return [
			MangoShop\Payment\Model\PaymentMethodsRepository::class => [
				DummyPaymentMethod::class,
				GoPayPaymentMethod::class,
			],
			MangoShop\Order\Model\OrderProcessingRepository::class => [
				$this->config['orderProcessingEntity'] ?? TestOrderProcessing::class,
			],
		];
	}


	public function getRepositoryClassNames(): array
	{
		$classNames = [];
		foreach ($this->getInnerExtensions() as $innerExtension) {
			if ($innerExtension instanceof IRepositoryClassProvider) {
				foreach ($innerExtension->getRepositoryClassNames() as $className) {
					$classNames[] = $className;
				}
			}
		}

		return $classNames;
	}


	public function getMigrationGroups(): array
	{
		$groups = [];
		foreach ($this->getInnerExtensions() as $innerExtension) {
			if ($innerExtension instanceof IMigrationGroupsProvider) {
				foreach ($innerExtension->getMigrationGroups() as $group) {
					$groups[] = $group;
				}
			}
		}

		return $groups;
	}


	/**
	 * @return CompilerExtension[]
	 */
	private function getInnerExtensions(): array
	{
		return [
			'mangoweb.mailQueue' => new \Mangoweb\MailQueue\Bridges\NetteDI\MailQueueExtension(),
			'mangoshop.core' => new MangoShop\Core\Bridges\NetteDI\CoreExtension(),
			'mangoshop.address' => new MangoShop\Address\Bridges\NetteDI\AddressExtension(),
			'mangoshop.channel' => new MangoShop\Channel\Bridges\NetteDI\ChannelExtension(),
			'mangoshop.locale' => new MangoShop\Locale\Bridges\NetteDI\LocaleExtension(),
			'mangoshop.money' => new MangoShop\Money\Bridges\NetteDI\MoneyExtension(),
			'mangoshop.order' => new MangoShop\Order\Bridges\NetteDI\OrderExtension(),
			'mangoshop.payment' => new MangoShop\Payment\Bridges\NetteDI\PaymentExtension(),
			'mangoshop.paymentGoPay' => new MangoShop\PaymentGoPay\Bridges\NetteDI\PaymentGoPayExtension(),
			'mangoshop.product' => new MangoShop\Product\Bridges\NetteDI\ProductExtension(),
			'mangoshop.promotion' => new MangoShop\Promotion\Bridges\NetteDI\PromotionExtension(),
			'mangoshop.shipping' => new MangoShop\Shipping\Bridges\NetteDI\ShippingExtension(),
		];
	}
}
