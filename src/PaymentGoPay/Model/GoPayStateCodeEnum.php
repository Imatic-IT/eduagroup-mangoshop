<?php declare(strict_types = 1);

namespace MangoShop\PaymentGoPay\Model;

use MabeEnum\Enum;
use MangoShop\Payment\Model\IExternalStateCodeEnum;


/**
 * @see https://doc.gopay.com/en/#payment-status
 *
 * @method static GoPayStateCodeEnum CREATED()
 * @method static GoPayStateCodeEnum PAYMENT_METHOD_CHOSEN()
 * @method static GoPayStateCodeEnum AUTHORIZED()
 * @method static GoPayStateCodeEnum PAID()
 * @method static GoPayStateCodeEnum CANCELED()
 * @method static GoPayStateCodeEnum TIMEOUTED()
 * @method static GoPayStateCodeEnum REFUNDED()
 * @method static GoPayStateCodeEnum PARTIALLY_REFUNDED()
 */
final class GoPayStateCodeEnum extends Enum implements IExternalStateCodeEnum
{
	public const CREATED = 'CREATED';
	public const PAYMENT_METHOD_CHOSEN = 'PAYMENT_METHOD_CHOSEN';
	public const AUTHORIZED = 'AUTHORIZED';
	public const PAID = 'PAID';
	public const CANCELED = 'CANCELED';
	public const TIMEOUTED = 'TIMEOUTED';
	public const REFUNDED = 'REFUNDED';
	public const PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';
}
