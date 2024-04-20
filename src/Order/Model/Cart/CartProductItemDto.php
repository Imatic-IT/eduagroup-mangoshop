<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Product\Model\ProductVariant;

class CartProductItemDto
{
	/** @var ProductVariant */
	private $variant;

	/** @var int */
	private $quantity;

	/** @var array */
	private $configuration;


	public function __construct(ProductVariant $variant, int $quantity, array $configuration = [])
	{
		$this->variant = $variant;
		$this->quantity = $quantity;
		$this->configuration = $configuration;
	}


	public function getVariant(): ProductVariant
	{
		return $this->variant;
	}


	public function getQuantity(): int
	{
		return $this->quantity;
	}


	public function getConfiguration(): array
	{
		return $this->configuration;
	}


	public function equalsIgnoringQuantity(self $other): bool
	{
		return $this->variant === $other->getVariant() && $this->configuration === $other->configuration;
	}
}
