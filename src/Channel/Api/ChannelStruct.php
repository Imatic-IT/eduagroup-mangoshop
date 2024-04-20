<?php declare(strict_types = 1);

namespace MangoShop\Channel\Api;

use MangoShop\Channel\Model\Channel;
use MangoShop\Channel\Model\CheckoutOptionGroup;
use MangoShop\Locale\Model\Locale;
use MangoShop\Product\Model\ProductPricingGroup;


class ChannelStruct
{
	/** @var string */
	public $name;

	/** @var Locale */
	public $defaultLocale;

	/** @var ProductPricingGroup */
	public $pricingGroup;

	/** @var CheckoutOptionGroup */
	public $checkoutOptionGroup;

	/** @var Locale[]|iterable */
	public $locales;


	/**
	 * @param Locale[]|iterable $locales
	 */
	public function __construct(
		string $name,
		Locale $defaultLocale,
		ProductPricingGroup $pricingGroup,
		CheckoutOptionGroup $checkoutOptionGroup,
		iterable $locales
	) {
		$this->name = $name;
		$this->defaultLocale = $defaultLocale;
		$this->pricingGroup = $pricingGroup;
		$this->checkoutOptionGroup = $checkoutOptionGroup;
		$this->locales = $locales;
	}


	public static function createFromChannel(Channel $channel): self
	{
		return new self(
			$channel->name,
			$channel->defaultLocale,
			$channel->pricingGroup,
			$channel->checkoutOptionGroup,
			$channel->locales
		);
	}
}
