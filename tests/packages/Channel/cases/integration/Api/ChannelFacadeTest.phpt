<?php declare(strict_types = 1);

namespace MangoShopTests\Channel\Cases\Integration\Api;

use MangoShop;
use MangoShop\Channel\Api\ChannelFacade;
use MangoShop\Channel\Api\ChannelStruct;
use MangoShop\Locale\Api\LocaleFacade;
use MangoShop\Money\Api\CurrencyFacade;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class ChannelFacadeTest extends TestCase
{
	public function testFlow(ChannelFacade $channelFacade, LocaleFacade $localeFacade, CurrencyFacade $currencyFacade)
	{
		$csLocale = $localeFacade->create('cs');
		$enLocale = $localeFacade->create('en');

		$czkCurrency = $currencyFacade->create('CZK');

		$pricingGroup = new MangoShop\Product\Model\ProductPricingGroup(
			'CZ pricing group',
			$czkCurrency
		);

		$checkoutOptionGroup = new MangoShop\Channel\Model\CheckoutOptionGroup(
			'standard checkout option group',
			$czkCurrency
		);

		$channel = $channelFacade->create(
			'CZ',
			new ChannelStruct(
				'Czech Channel',
				$csLocale,
				$pricingGroup,
				$checkoutOptionGroup,
				[$csLocale, $enLocale]
			)
		);

		Assert::same('CZ', $channel->code);
		Assert::same('Czech Channel', $channel->name);
		Assert::same($csLocale, $channel->defaultLocale);
		Assert::same($pricingGroup, $channel->pricingGroup);
		Assert::same($checkoutOptionGroup, $channel->checkoutOptionGroup);
		Assert::same([$csLocale, $enLocale], $channel->locales->fetchAll());

		$channelStruct = ChannelStruct::createFromChannel($channel);
		$channelStruct->defaultLocale = $enLocale;
		$channelFacade->update($channel, $channelStruct);
		Assert::same($enLocale, $channel->defaultLocale);
		Assert::same([$csLocale, $enLocale], $channel->locales->fetchAll());

		$channelStruct = ChannelStruct::createFromChannel($channel);
		$channelStruct->name = 'New Name';
		$channelFacade->update($channel, $channelStruct);
		Assert::same('New Name', $channel->name);
	}
}


ChannelFacadeTest::run($containerFactory);
