<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Inc;

use MabeEnum\Enum;
use MangoShop\Payment\Model\IExternalStateCodeEnum;


/**
 * @method static DummyExternalStateCodeEnum CREATED()
 * @method static DummyExternalStateCodeEnum APPROVED()
 * @method static DummyExternalStateCodeEnum FAILED()
 */
final class DummyExternalStateCodeEnum extends Enum implements IExternalStateCodeEnum
{
	public const CREATED = 'created';
	public const APPROVED = 'approved';
	public const FAILED = 'failed';
}
