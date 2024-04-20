<?php declare(strict_types = 1);

namespace MangoShop\Payment\Bridges\NetteDI;

use Mangoweb\NetteScopeExtension\ScopeExtension;
use Nette;


class PaymentBootstrapExtension extends ScopeExtension
{
	public static function getTagName(): string
	{
		return 'shop.api';
	}


	protected function createInnerConfigurator(): Nette\Configurator
	{
		$configurator = parent::createInnerConfigurator();
		$configurator->addConfig(__DIR__ . '/config.bootstrap-payment.neon');

//		echo "<pre>";
//		var_dump($configurator);die;
		return $configurator;
	}
}
