<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MabeEnum\Enum;

/**
 * @method static BicistickersOrderProcessingStateEnum CREATED()
 * @method static BicistickersOrderProcessingStateEnum GENERATING_PDFS()
 * @method static BicistickersOrderProcessingStateEnum WAITING_TO_PRINT()
 * @method static BicistickersOrderProcessingStateEnum PRINTED()
 * @method static BicistickersOrderProcessingStateEnum POSTPONED()
 */
final class BicistickersOrderProcessingStateEnum extends Enum
{
	public const CREATED = 'created';
	public const GENERATING_PDFS = 'generating_pdfs';
	public const WAITING_TO_PRINT = 'waiting_to_print';
	public const PRINTED = 'printed';
	public const POSTPONED = 'postponed';
}
