<?php declare(strict_types = 1);

namespace MangoShop\Channel\Bridges\NetteDI;

use Mangoweb\NetteScopeExtension\ScopeExtension;
use Nette;


class OrderBootstrapExtension extends ScopeExtension
{
	public static function getTagName(): string
	{
		return 'shop.api';
	}


	protected function createInnerConfigurator(): Nette\Configurator
	{
		$configurator = parent::createInnerConfigurator();
		$configurator->addConfig(__DIR__ . '/config.bootstrap-order.neon');

		return $configurator;
	}
}
