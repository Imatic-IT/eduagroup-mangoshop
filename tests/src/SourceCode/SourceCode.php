<?php declare(strict_types = 1);

namespace MangoShopTests\Analysis;

use Nette\Utils\FileSystem;


class SourceCode
{

	public const BASH = 'bash';
	public const BATCH = 'batch';
	public const INI = 'ini';
	public const LATTE = 'latte';
	public const MARKDOWN = 'markdown';
	public const NEON = 'neon';
	public const ORM = 'orm';
	public const ORM_ENTITY = 'orm_entity';
	public const SOURCE = 'source';
	public const TEST_CASE = 'test_case';


	/** @var \SplFileInfo */
	private $fileInfo;

	/** @var NULL|string */
	private $sourceCode;

	/** @var array */
	private $tags;


	public function __construct(\SplFileInfo $fileInfo, array $tags)
	{
		$this->fileInfo = $fileInfo;
		$this->tags = $tags;
	}


	public function getFileInfo(): \SplFileInfo
	{
		return $this->fileInfo;
	}


	public function getSource(): string
	{
		if ($this->sourceCode === NULL) {
			$this->sourceCode = FileSystem::read($this->fileInfo->getPathname());
		}
		return $this->sourceCode;
	}


	public function is(string $tag): bool
	{
		return in_array($tag, $this->tags, TRUE);
	}


	public function __toString(): string
	{
		return $this->getFileInfo()->getPathname();
	}

}
