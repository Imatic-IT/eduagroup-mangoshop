<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


class PaymentInitializationRequest
{
	/** @var null|string */
	public $customerEmail;

	/** @var null|string */
	public $orderNumber;

	/** @var PaymentOrderItem[] */
	public $orderItems;


	/**
	 * @param PaymentOrderItem[] $orderItems
	 */
	public function __construct(?string $customerEmail, ?string $orderNumber, array $orderItems = [])
	{
		$this->customerEmail = $customerEmail;
		$this->orderNumber = $orderNumber;
		$this->orderItems = $orderItems;
	}
}
