<?php declare(strict_types = 1);

namespace MangoShopTests\Analysis;

use Nette\Utils\Finder;
use Nette\Utils\Strings;


class SourceCodeProvider
{

	/** @var array */
	private $paths;

	/** @var string */
	private $testCasesDir;


	public function __construct(array $paths, string $testCasesDir)
	{
		$this->paths = $paths;
		$this->testCasesDir = $testCasesDir;
	}


	/**
	 * @return \Generator|SourceCode[]
	 * @throws \Nette\InvalidStateException
	 */
	public function getFiles(): \Generator
	{
		foreach (Finder::findFiles('*')->from($this->paths) as $info)
		{
			$tags = iterator_to_array($this->getTags($info));
			$file = new SourceCode($info, $tags);

			yield $file;
		}
	}


	/**
	 * @param \SplFileInfo $info
	 * @return \generator|string[]
	 * @see SourceCode
	 */
	private function getTags(\SplFileInfo $info): \generator
	{
		if (in_array($info->getExtension(), ['neon'], TRUE)) {
			yield SourceCode::NEON;
		}

		if (in_array($info->getExtension(), ['ini'], TRUE)) {
			yield SourceCode::INI;
		}

		if (in_array($info->getExtension(), ['md'], TRUE)) {
			yield SourceCode::MARKDOWN;
		}

		if (in_array($info->getExtension(), ['latte'], TRUE)) {
			yield SourceCode::LATTE;
		}

		if (in_array($info->getExtension(), ['sh'], TRUE)) {
			yield SourceCode::BASH;
		}

		if (in_array($info->getExtension(), ['bat'], TRUE)) {
			yield SourceCode::BATCH;
		}

		// Normalize Windows path
		$path = str_replace('\\', '/', $info->getPathname());

		if (Strings::endsWith($path, '/bin/console')) {
			yield SourceCode::BASH;
		}

		if (in_array($info->getExtension(), ['php', 'phpt'], TRUE)) {
			yield SourceCode::SOURCE;
		}

		if (Strings::startsWith($path, $this->testCasesDir)) {
			yield SourceCode::TEST_CASE;
		}

	}

}
