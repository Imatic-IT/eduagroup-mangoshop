<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


class PaymentOrderItem
{
	/** @var PaymentOrderItemTypeEnum */
	public $type;

	/** @var null|string */
	public $productUrl;

	/** @var null|string */
	public $ean;

	/** @var int */
	public $quantity;

	/** @var string */
	public $name;

	/** @var int */
	public $totalAmountCents;


	public function __construct(
		PaymentOrderItemTypeEnum $type,
		?string $productUrl,
		?string $ean,
		int $quantity,
		string $name,
		int $totalAmountCents
	) {
		$this->type = $type;
		$this->productUrl = $productUrl;
		$this->ean = $ean;
		$this->quantity = $quantity;
		$this->name = $name;
		$this->totalAmountCents = $totalAmountCents;
	}
}
