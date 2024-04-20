<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MabeEnum\Enum;


/**
 * @method static FailureReasonEnum TIMEOUTED()
 * @method static FailureReasonEnum CANCELED()
 * @method static FailureReasonEnum DENIED()
 * @method static FailureReasonEnum REVERSED()
 * @method static FailureReasonEnum REFUNDED()
 * @method static FailureReasonEnum DRIVER()
 * @method static FailureReasonEnum UNKNOWN()
 */
final class FailureReasonEnum extends Enum
{
	public const TIMEOUTED = 'timeouted';
	public const CANCELED = 'canceled';
	public const DENIED = 'denied';
	public const REVERSED = 'reversed';
	public const REFUNDED = 'refunded';
	public const DRIVER = 'driver'; // bad request, network error etc.
	public const UNKNOWN = 'unknown';
}
