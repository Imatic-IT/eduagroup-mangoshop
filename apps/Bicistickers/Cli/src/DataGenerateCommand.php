<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Cli;

use MangoShop\Bicistickers\Model\DataGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class DataGenerateCommand extends Command
{
	/** @var string */
	protected static $defaultName = 'app:data-generate';

	/** @var DataGenerator */
	private $dataGenerator;


	public function __construct(DataGenerator $dataGenerator)
	{
		parent::__construct();
		$this->dataGenerator = $dataGenerator;
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->dataGenerator->generate(function (string $line) use ($output) {
			$output->writeln($line);
		});
	}
}
