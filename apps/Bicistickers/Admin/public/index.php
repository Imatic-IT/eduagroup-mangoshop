<?php declare(strict_types = 1);

use MangoShop\Bicistickers\Bridges\NetteConfigurator\ConfiguratorFactory;

require __DIR__ . '/../../../../vendor/autoload.php';

$configurator = ConfiguratorFactory::create(__DIR__ . '/../src', __DIR__ . '/../../../../log', __DIR__ . '/../../../../temp');

$configurator->addConfig(__DIR__ . '/../config/config.neon');
$configurator->addConfig(__DIR__ . '/../config/local.neon');
$container = $configurator->createContainer();

$application = $container->getService('application.application');

assert($application instanceof Nette\Application\Application);
$application->run();
