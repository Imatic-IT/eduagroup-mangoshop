<?php declare(strict_types = 1);

$configurator = require __DIR__ . '/../vendor/autoload.php';

$root = str_replace('\\', '/', dirname(__DIR__));
$params = [
	'logDir' => "$root/log",
	'tempDir' => "$root/temp",
	'rootDir' => $root,
	'srcDir' => "$root/src",
	'configDir' => "$root/tests/config",
	'testDir' => "$root/tests",
];

$configurator = new \Nette\Configurator();
$configurator->defaultExtensions = [
	'php' => Nette\DI\Extensions\PhpExtension::class,
	'constants' => Nette\DI\Extensions\ConstantsExtension::class,
	'extensions' => Nette\DI\Extensions\ExtensionsExtension::class,
	'decorator' => Nette\DI\Extensions\DecoratorExtension::class,
	'cache' => [Nette\Bridges\CacheDI\CacheExtension::class, ['%tempDir%']],
	'di' => [Nette\DI\Extensions\DIExtension::class, ['%debugMode%']],
	'inject' => Nette\DI\Extensions\InjectExtension::class,
];

$configurator->setDebugMode(true);

\Tracy\Debugger::$logSeverity = E_NOTICE | E_WARNING;

$configurator->setTempDirectory($params['tempDir']);
$loader = $configurator->createRobotLoader()
	->addDirectory($params['srcDir'])
	->addDirectory($params['testDir'])
	->addDirectory(__DIR__ . '/../packages')
	->addDirectory(__DIR__ . '/../apps')
	->excludeDirectory($params['testDir'] . '/phpstan')
	->setAutoRefresh(false)
	->register();
$configurator->addServices([
	'mango.tester.robotLoader' => $loader,
]);

$configurator->addConfig(__DIR__ . '/config/infrastructure.neon');

$configurator->addConfig(__DIR__ . '/config/local.neon');

$configurator->addParameters($params);

date_default_timezone_set('UTC');

Tracy\Debugger::$maxLength = 10000;
Tracy\Debugger::$maxDepth = 7;
Tester\Dumper::$maxPathSegments = 0;

Tester\Environment::setup();

\Mangoweb\Clock\Clock::$allowMock = true;

set_exception_handler(function ($e) {
	if ($e instanceof \Mangoweb\Tester\DatabaseTester\AggregatedAssertException) {
		foreach ($e->getExceptions() as $e2) {
			echo \Tester\Environment::$debugMode ? \Tester\Dumper::dumpException($e2) : "\nError: {$e2->getMessage()}\n";
		}
	}
	\Tester\Environment::handleException($e);
});

return $configurator;
