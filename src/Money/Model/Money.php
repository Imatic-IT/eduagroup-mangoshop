<?php declare(strict_types = 1);

namespace MangoShop\Money\Model;


class Money
{
	/**
	 * Always assuming hundred cents per 1 unit, 2550 cents = 25.50 $currency.
	 * Currency does not effect how many cents are used per one unit.
	 * See https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support
	 * for a list of currencies that would require special handling (regardless of whether Stripe is used).
	 * @var int
	 */
	private $cents;

	/** @var Currency */
	private $currency;


	public function __construct(int $cents, Currency $currency)
	{
		$this->cents = $cents;
		$this->currency = $currency;
	}


	public function getCents(): int
	{
		return $this->cents;
	}


	public function getCurrency(): Currency
	{
		return $this->currency;
	}


	/**
	 * @return Money new instance
	 */
	public function add(self $money): self
	{
		$this->assertCurrenciesMatch($money);

		return new self($this->cents + $money->getCents(), $this->currency);
	}


	/***
	 * @return Money new instance
	 */
	public function subtract(self $subtrahend): self
	{
		$this->assertCurrenciesMatch($subtrahend);

		return new self($this->cents - $subtrahend->getCents(), $this->currency);
	}


	/**
	 * @return Money new instance
	 */
	public function multiply(int $multiplier): self
	{
		return new self($this->cents * $multiplier, $this->currency);
	}


	public function percentMultiply(float $multiplier): self
	{
		assert($multiplier >= 0.0 && $multiplier <= 1.0);
		return new self((int) round($this->cents * $multiplier), $this->currency);
	}


	public function toNegative(): self
	{
		assert($this->cents >= 0);
		return new self(-$this->cents, $this->currency);
	}


	public function isZero(): bool
	{
		return $this->cents === 0;
	}


	public function isEqual(self $other): bool
	{
		return $this->cents === $other->cents && $this->currency === $other->currency;
	}


	public function roundNegativeToZero(): self
	{
		return new self(max(0, $this->cents), $this->currency);
	}


	private function assertCurrenciesMatch(self $otherMoney): void
	{
		if ($this->currency !== $otherMoney->getCurrency()) {
			throw new \LogicException("Currencies do not match {$this->currency->code} !== {$otherMoney->getCurrency()->code}");
		}
	}
}
