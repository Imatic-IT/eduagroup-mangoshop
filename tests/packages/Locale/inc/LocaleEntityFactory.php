<?php declare(strict_types = 1);

namespace MangoShopTests\Locale\Inc;

use MangoShop\Locale\Model\Locale;
use MangoShopTests\EntityFactory;


class LocaleEntityFactory extends EntityFactory
{
	private const LOCALE_CODES = ['cs_CZ', 'sk_SK', 'en_US', 'en_GB', 'de_DE', 'fr_FR', 'sv_SE'];

	/** @var int */
	private $localeCodeIndex = 0;


	public function createLocale(array $data): Locale
	{
		$this->verifyData(['code'], $data);
		$code = $data['code'] ?? self::LOCALE_CODES[$this->localeCodeIndex++];

		return new Locale($code);
	}
}
