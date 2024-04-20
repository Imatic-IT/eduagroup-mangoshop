<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use MangoShop\Channel\Model\Channel;
use MangoShop\Channel\Model\CheckoutOptionGroup;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Currency;
use MangoShop\Product\Model\ProductPricingGroup;


/**
 * @property-read Channel             $channel             {m:1 Channel, oneSided=true}
 * @property-read Currency            $currency            {m:1 Currency, oneSided=true}
 * @property-read Locale              $locale              {m:1 Locale, oneSided=true}
 * @property-read Session             $session             {m:1 Session, oneSided=true}
 * @property-read ProductPricingGroup $productPricingGroup {virtual}
 * @property-read CheckoutOptionGroup $checkoutOptionGroup {virtual}
 */
class OrderContext extends Entity
{
	public function __construct(Channel $channel, Currency $currency, Locale $locale, Session $session)
	{
		parent::__construct();

		assert($channel->pricingGroup->currency === $currency);
		assert($channel->getRelationship('locales')->has($locale));

		$this->setReadOnlyValue('channel', $channel);
		$this->setReadOnlyValue('currency', $currency);
		$this->setReadOnlyValue('locale', $locale);
		$this->setReadOnlyValue('session', $session);
	}


	public function withLocale(Locale $locale): self
	{
		if ($locale === $this->locale) {
			return $this;
		}

		return new self($this->channel, $this->currency, $locale, $this->session);
	}


	protected function getterProductPricingGroup(): ProductPricingGroup
	{
		return $this->channel->pricingGroup;
	}


	protected function getterCheckoutOptionGroup(): CheckoutOptionGroup
	{
		return $this->channel->checkoutOptionGroup;
	}
}
