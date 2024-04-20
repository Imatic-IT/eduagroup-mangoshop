<?php declare(strict_types = 1);

namespace Mangoweb\Clock;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;


class Clock
{
	/** @var bool */
	public static $allowMock = false;

	/** @var null|DateTimeImmutable */
	private static $now;


	public static function now(): DateTimeImmutable
	{
		if (self::$now === null) {
			$now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
			$now->setTimestamp($now->getTimestamp()); // trim microseconds
			self::$now = $now;
		}

		return self::$now;
	}


	/**
	 * You should use this only in workers
	 */
	public static function refresh(): void
	{
		if (self::$allowMock) {
			return;
		}
		self::$now = null;
		self::now();
	}


	/**
	 * @param DateTimeImmutable|string $now
	 */
	public static function mockNow($now): void
	{
		if (!self::$allowMock) {
			throw new \LogicException('Mocking Clock not allowed without explicit permit');
		}

		if ($now instanceof DateTimeImmutable) {
			self::$now = $now;

		} elseif (is_string($now)) {
			self::$now = new DateTimeImmutable($now);

		} else {
			throw new \LogicException();
		}
	}


	/**
	 * @param  DateTimeImmutable|string $now
	 * @param  callable                 $callback
	 * @return mixed value return by invoking callback
	 */
	public static function mockNowScoped($now, callable $callback)
	{
		$before = self::$now;

		try {
			self::mockNow($now);
			return $callback();

		} finally {
			self::$now = $before;
		}
	}


	public static function addSeconds(int $seconds): void
	{
		if ($seconds < 0) {
			$seconds = (int) abs($seconds);
			self::mockNow(self::now()->sub(new DateInterval("PT{$seconds}S")));

		} else {
			self::mockNow(self::now()->add(new DateInterval("PT{$seconds}S")));
		}
	}


	public static function addHours(int $hours): void
	{
		if ($hours < 0) {
			$hours = (int) abs($hours);
			self::mockNow(self::now()->sub(new DateInterval("PT{$hours}H")));

		} else {
			self::mockNow(self::now()->add(new DateInterval("PT{$hours}H")));
		}
	}


	public static function addDays(int $days): void
	{
		if ($days < 0) {
			$days = (int) abs($days);
			self::mockNow(self::now()->sub(new DateInterval("P{$days}D")));

		} else {
			self::mockNow(self::now()->add(new DateInterval("P{$days}D")));
		}
	}
}
