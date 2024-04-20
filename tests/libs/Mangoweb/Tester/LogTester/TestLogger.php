<?php declare(strict_types = 1);

namespace Mangoweb\Tester\LogTester;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;


class TestLogger extends AbstractLogger
{
	/** @var LogEntry[] */
	public $entries = [];


	public function log($level, $message, array $context = [])
	{
		$this->entries[] = new LogEntry($level, $message, $context);
	}


	/**
	 * @param  string $minPriority
	 * @return LogEntry[]
	 */
	public function getEntries(string $minPriority = LogLevel::DEBUG): array
	{
		$filtered = [];
		$requiredLevel = $this->getPriorityLevel($minPriority);
		foreach ($this->entries as $entry) {
			if ($this->getPriorityLevel($entry->level) >= $requiredLevel) {
				$filtered[] = $entry;
			}
		}

		return $filtered;
	}


	private function getPriorityLevel(string $priority): int
	{
		static $table = [
			LogLevel::DEBUG => 0,
			LogLevel::INFO => 1,
			LogLevel::WARNING => 2,
			LogLevel::ERROR => 3,
			LogLevel::CRITICAL => 4,
			LogLevel::ALERT => 5,
			LogLevel::EMERGENCY => 6,
		];

		return $table[$priority] ?? 9;
	}
}
