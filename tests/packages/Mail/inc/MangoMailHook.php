<?php declare(strict_types = 1);

namespace MangowebTests\MailQueue\Inc;

use Mangoweb\MailQueue\Bridges\NetteDI\MailQueueExtension;
use Mangoweb\Tester\Infrastructure\Container\IAppContainerHook;
use Nette\Configurator;
use Nette\DI\Container;
use Nette\DI\ContainerBuilder;

class MangoMailHook implements IAppContainerHook
{
	public function onConfigure(Configurator $configurator): void
	{
		$configurator->addConfig([
			'extensions' => [
				'mangoweb.mailQueue' => MailQueueExtension::class,
			],
		]);
	}


	public function onCompile(ContainerBuilder $builder): void
	{
	}


	public function onCreate(Container $applicationContainer): void
	{
	}
}
