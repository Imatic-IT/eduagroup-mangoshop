<?php declare(strict_types = 1);

assert($this instanceof \Tester\Runner\CliTester);
if (!empty($this->options['--coverage'])) {
	file_put_contents($this->options['--coverage'], serialize([]));
}

assert(isset($runner) && $runner instanceof \Tester\Runner\Runner);
if ($reportDir = getenv('CIRCLE_TEST_REPORTS')) {
	$runner->outputHandlers[] = new Tester\Runner\Output\JUnitPrinter("$reportDir/coverage.xml");
}
