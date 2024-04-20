<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Cases\Integration\Api;

use MangoShop;
use MangoShop\Address\Model\Address;
use MangoShop\Address\Model\CountryState;
use MangoShop\Address\Model\Phone;
use MangoShop\Channel\Model\Channel;
use MangoShop\Order\Api\CartFacade;
use MangoShop\Order\Api\CartUpdateRequest;
use MangoShop\Order\Model\OrderBillingInfo;
use MangoShop\Order\Model\OrderShippingInfo;
use MangoShop\Order\Model\Session;
use MangoShop\Product\Model\ProductVariant;
use MangoShop\Promotion\Model\PromotionCoupon;
use MangoShop\Shipping\Model\ShippingMethod;
use Mangoweb\Tester\Infrastructure\TestCase;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Product\Inc\ProductEntityFactory;
use Nextras\Orm\Model\IModel;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class CartFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testGetCart(IModel $orm, CartFacade $cartFacade)
	{
		$session = $this->entityGenerator->createInOrm($orm, Session::class);
		$channel = $this->entityGenerator->createInOrm($orm, Channel::class);

		$cart = $cartFacade->getCart($session->token, $channel);
		Assert::same($session, $cart->context->session);
		Assert::same($channel, $cart->context->channel);
	}


	public function testUpdateCart(IModel $orm, CartFacade $cartFacade)
	{
		$session = $this->entityGenerator->createInOrm($orm, Session::class);
		$channel = $this->entityGenerator->createInOrm($orm, Channel::class);
		$state = $this->entityGenerator->create(CountryState::class);
		$shippingMethod = $this->entityGenerator->create(ShippingMethod::class);
		$productVariant = $this->entityGenerator->create(ProductVariant::class);
		$this->entityGenerator->create(...ProductEntityFactory::pricingFor($productVariant, $channel->pricingGroup));
		$promotionCoupon = $this->entityGenerator->create(PromotionCoupon::class);

		$cart = $cartFacade->getCart($session->token, $channel);

		$cartRequest = new CartUpdateRequest($cart);

		$address = new Address('John Doe', 'Jungmannove 34', '', 'Praha 1', '11000', $state, $state->country);
		$address2 = new Address('John Doe 2', 'Jungmannove 34', '', 'Praha 1', '11000', $state, $state->country);

		$cartRequest = $cartRequest->withShippingInfo(new OrderShippingInfo($address, new Phone('+420111222333')));
		$cartRequest = $cartRequest->withBillingInfo(new OrderBillingInfo($address2, null, '123456'));
		$cartRequest = $cartRequest->withShippingMethod($shippingMethod);
		$cartRequest = $cartRequest->withProductItem($productVariant, 1);
		$cartRequest = $cartRequest->withPromotionCoupon($promotionCoupon);

		$newCart = $cartFacade->updateCart($cartRequest);

		Assert::same($cart, $newCart->previousVersion);

		Assert::same($address, $newCart->shippingInfo->address);
		Assert::same('+420111222333', (string) $newCart->shippingInfo->phone);

		Assert::same($address2, $newCart->billingInfo->address);
		Assert::same('123456', (string) $newCart->billingInfo->companyIdentifier);

		Assert::same($shippingMethod, $newCart->shippingMethod);

		Assert::count(1, $newCart->productItems);
		Assert::count(1, $newCart->promotions);
	}
}


CartFacadeTest::run($containerFactory);
