<?php declare(strict_types = 1);

namespace Mangoweb\Tester\LogTester;

use Psr\Log\LogLevel;
use Tester\Assert;


class LogTester
{
	/** @var TestLogger */
	private $logger;


	public function __construct(TestLogger $logger)
	{
		$this->logger = $logger;
	}


	public function assertNone(string $minPriority = LogLevel::DEBUG): void
	{
		$logEntries = $this->logger->getEntries($minPriority);
		$logEntriesCount = count($logEntries);

		if ($logEntriesCount > 0) {
			Assert::fail(sprintf(
				implode("\n", [
					'%d log %s with priority >= %s not been consumed by LogTester.',
					'    first unconsumed entry message: %s',
					'    first unconsumed entry level:   %s',
				]),
				$logEntriesCount,
				$logEntriesCount === 1 ? 'entry' : 'entries',
				$minPriority,
				$logEntries[0]->message,
				$logEntries[0]->level
			));
		}
	}


	public function consumeOne(?string $level = null, ?string $message = null, ?array $context = null): LogEntry
	{
		$entry = array_shift($this->logger->entries);
		Assert::type(LogEntry::class, $entry);

		if ($level !== null) {
			Assert::same($level, $entry->level);
		}

		if ($message !== null) {
			Assert::same($message, $entry->message);
		}

		if ($context !== null) {
			Assert::equal($context, $entry->context);
		}

		return $entry;
	}


	/**
	 * @return LogEntry[]
	 */
	public function consumeAll(?callable $callback = null): array
	{
		$consumed = $this->logger->entries;

		if ($callback !== null) {
			$callback($this);
			$this->assertNone();

		} else {
			$this->logger->entries = [];
		}

		return $consumed;
	}
}
