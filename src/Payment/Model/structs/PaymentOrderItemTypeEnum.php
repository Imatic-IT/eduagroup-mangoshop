<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MabeEnum\Enum;


/**
 * @method static PaymentOrderItemTypeEnum PRODUCT()
 * @method static PaymentOrderItemTypeEnum SHIPPING()
 * @method static PaymentOrderItemTypeEnum PROMOTION()
 */
final class PaymentOrderItemTypeEnum extends Enum
{
	public const PRODUCT = 'product';
	public const SHIPPING = 'shipping';
	public const PROMOTION = 'promotion';
}
