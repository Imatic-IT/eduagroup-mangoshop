<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MabeEnum\Enum;


/**
 * @method static InternalStateCodeEnum CREATED()
 * @method static InternalStateCodeEnum APPROVED()
 * @method static InternalStateCodeEnum FAILED()
 */
final class InternalStateCodeEnum extends Enum
{
	public const CREATED = 'created';
	public const APPROVED = 'approved';
	public const FAILED = 'failed';
}
