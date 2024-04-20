<?php declare(strict_types = 1);

namespace Mangoweb\Tester\LogTester;


class LogEntry
{
	/** @var string */
	public $level;

	/** @var string */
	public $message;

	/** @var array */
	public $context;


	public function __construct(string $level, string $message, array $context = [])
	{
		$this->level = $level;
		$this->message = $message;
		$this->context = $context;
	}
}
