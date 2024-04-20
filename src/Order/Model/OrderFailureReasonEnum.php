<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MabeEnum\Enum;


/**
 * @method static OrderFailureReasonEnum PAYMENT_FAILED()
 * @method static OrderFailureReasonEnum CANCEL_SHOP()
 * @method static OrderFailureReasonEnum CANCEL_CUSTOMER()
 * @method static OrderFailureReasonEnum CANCEL_SYSTEM()
 * @method static OrderFailureReasonEnum RETURN_CARRIER()
 * @method static OrderFailureReasonEnum RETURN_CUSTOMER()
 */
final class OrderFailureReasonEnum extends Enum
{
	public const PAYMENT_FAILED = 'payment_failed';
	public const CANCEL_SHOP = 'cancel_shop';
	public const CANCEL_CUSTOMER = 'cancel_customer';
	public const CANCEL_SYSTEM = 'cancel_system';
	public const RETURN_CARRIER = 'return_carrier';
	public const RETURN_CUSTOMER = 'return_customer';
}
