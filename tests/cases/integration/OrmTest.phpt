<?php declare(strict_types = 1);

namespace MangoShopTests;

use MangoShop;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nextras\Orm\Model\Model;
use Tester\Assert;


$containerFactory = require __DIR__ . '/../../bootstrap.php';


/**
 * Checks that all tables exists and entities are valid
 * @testCase
 */
class OrmTest extends TestCase
{

	public function testRepos(Model $orm)
	{
		$repositories = (\Closure::bind(
			function () {
				return $this->configuration[1];
			},
			$orm,
			Model::class
		))();
		Assert::same([
			MangoShop\Address\Model\AddressesRepository::class,
			MangoShop\Address\Model\CountriesRepository::class,
			MangoShop\Address\Model\CountryStatesRepository::class,
			MangoShop\Channel\Model\ChannelsRepository::class,
			MangoShop\Channel\Model\CheckoutOptionsRepository::class,
			MangoShop\Channel\Model\CheckoutOptionGroupRepository::class,
			MangoShop\Locale\Model\LocalesRepository::class,
			MangoShop\Money\Model\CurrenciesRepository::class,
			MangoShop\Order\Model\CartsRepository::class,
			MangoShop\Order\Model\CartProductItemsRepository::class,
			MangoShop\Order\Model\CartPromotionsRepository::class,
			MangoShop\Order\Model\CustomersRepository::class,
			MangoShop\Order\Model\OrdersRepository::class,
			MangoShop\Order\Model\OrderBillingInfoRepository::class,
			MangoShop\Order\Model\OrderContextsRepository::class,
			MangoShop\Order\Model\OrderProcessingRepository::class,
			MangoShop\Order\Model\OrderProductItemsRepository::class,
			MangoShop\Order\Model\OrderPromotionsRepository::class,
			MangoShop\Order\Model\OrderShippingInfoRepository::class,
			MangoShop\Order\Model\SessionsRepository::class,
			MangoShop\Payment\Model\PaymentsRepository::class,
			MangoShop\Payment\Model\PaymentMethodsRepository::class,
			MangoShop\Payment\Model\PaymentStatesRepository::class,
			MangoShop\Product\Model\ProductsRepository::class,
			MangoShop\Product\Model\ProductPricingGroupsRepository::class,
			MangoShop\Product\Model\ProductTranslationsRepository::class,
			MangoShop\Product\Model\ProductVariantsRepository::class,
			MangoShop\Product\Model\ProductVariantPricingsRepository::class,
			MangoShop\Product\Model\ProductVariantTranslationsRepository::class,
			MangoShop\Promotion\Model\PromotionsRepository::class,
			MangoShop\Promotion\Model\PromotionCouponsRepository::class,
			MangoShop\Shipping\Model\ShippingMethodsRepository::class,
		], array_values($repositories));
		foreach ($repositories as $name => $class) {
			$orm->getRepository($class)->findAll()->fetch();
		}
	}
}


OrmTest::run($containerFactory);
