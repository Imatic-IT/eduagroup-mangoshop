<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Inc;

use MabeEnum\Enum;

/**
 * @method static static CREATED()
 * @method static static PACKING()
 * @method static static DISPATCHED()
 */
final class TestOrderProcessingStateEnum extends Enum
{
	public const CREATED = 'created';
	public const PACKING = 'packing';
	public const DISPATCHED = 'dispatched';
}
