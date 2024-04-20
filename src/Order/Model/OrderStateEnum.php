<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MabeEnum\Enum;


/**
 * @method static OrderStateEnum WAITING_FOR_PAYMENT()
 * @method static OrderStateEnum PROCESSING()
 * @method static OrderStateEnum DISPATCHED()
 * @method static OrderStateEnum FULFILLED()
 * @method static OrderStateEnum FAILED()
 */
final class OrderStateEnum extends Enum
{
	public const WAITING_FOR_PAYMENT = 'waiting_for_payment';
	public const PROCESSING = 'processing';
	public const DISPATCHED = 'dispatched';
	public const FULFILLED = 'fulfilled';
	public const FAILED = 'failed';
}
