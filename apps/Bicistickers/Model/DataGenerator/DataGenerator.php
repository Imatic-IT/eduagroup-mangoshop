<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use Faker\Factory;
use Faker\Generator;
use MangoShop\Address\Model\Address;
use MangoShop\Address\Model\Country;
use MangoShop\Address\Model\CountryState;
use MangoShop\Address\Model\Email;
use MangoShop\Address\Model\Phone;
use MangoShop\Channel\Model\Channel;
use MangoShop\Channel\Model\CheckoutOption;
use MangoShop\Core\NextrasOrm\Entity;
use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Currency;
use MangoShop\Order\Model\Cart;
use MangoShop\Order\Model\CartProductItemDto;
use MangoShop\Order\Model\Customer;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderBillingInfo;
use MangoShop\Order\Model\OrderContext;
use MangoShop\Order\Model\OrderShippingInfo;
use MangoShop\Order\Model\Session;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Product\Model\Product;
use MangoShop\Product\Model\ProductPricingGroup;
use MangoShop\Product\Model\ProductVariant;
use MangoShop\Product\Model\ProductVariantPricing;
use MangoShop\Shipping\Model\ShippingMethod;
use Mangoweb\Clock\Clock;
use Nextras\Orm\Model\Model;

class DataGenerator
{
	/** @var array of [type][] => entity */
	private $entities = [];

	/** @var Model */
	private $orm;

	/** @var Generator */
	private $faker;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(Model $model, TransactionManager $transactionManager)
	{
		$this->orm = $model;
		$this->transactionManager = $transactionManager;
	}


	public function generate(callable $outputWriter): void
	{
		$outputWriter('starting');
		$this->faker = Factory::create('en_US');
		$this->faker->seed(1);
		Clock::$allowMock = true;

		$transaction = $this->transactionManager->begin();

		$outputWriter('loading dummy data');
		$this->load(Channel::class);
		$this->load(CheckoutOption::class);
		$this->load(Currency::class);
		$this->load(Country::class);
		$this->load(CountryState::class);
		$this->load(Locale::class);
		$this->load(PaymentMethod::class, ['code' => 'gopay']);
		$this->load(ProductPricingGroup::class);
		$this->load(ProductVariantPricing::class);
		$this->load(Product::class);
		$this->load(ProductVariant::class);
		$this->load(ShippingMethod::class);
		$outputWriter('loaded');

		$outputWriter('creating orders');
		$this->createOrders($transaction);
		$outputWriter('loaded');

		$this->transactionManager->flush($transaction);
		$outputWriter('finished');
	}


	public function createOrders(Transaction $transaction): void
	{
		for ($i = 0; $i < 100; $i++) {
			$cart = $this->createCart();
			assert($cart->paymentMethod !== null);
			$payment = new Payment($cart->paymentMethod, $cart->totalPrice, $cart->context->locale);
			$order = new Order($cart, $payment);
			$this->add($transaction, $order);
		}
	}


	private function createCart(): Cart
	{
		$channel = $this->random(Channel::class);
		assert($channel instanceof Channel);

		$country = $this->random(Country::class);
		assert($country instanceof Country);

		$shippingAddress = new Address(
			$this->faker->name,
			$this->faker->streetAddress,
			'',
			$this->faker->city,
			$this->faker->postcode,
			null,
			$country
		);

		$items = [];
		$itemsCount = $this->faker->numberBetween(1, 5);
		for ($i = 0; $i < $itemsCount; $i++) {
			$variant = $this->random(ProductVariant::class);
			assert($variant instanceof ProductVariant);
			$items[] = new CartProductItemDto($variant, $this->faker->numberBetween(1, 3));
		}

		$shippingMethod = $this->random(ShippingMethod::class);
		assert($shippingMethod instanceof ShippingMethod);

		$paymentMethod = $this->random(PaymentMethod::class);
		assert($paymentMethod instanceof PaymentMethod);

		return new Cart(
			new OrderContext($channel, $channel->currency, $channel->defaultLocale, new Session()),
			new Customer(new Email($this->faker->email)),
			new OrderShippingInfo($shippingAddress, new Phone('+420' . $this->faker->randomNumber(9))),
			$shippingMethod,
			new OrderBillingInfo($this->faker->boolean() ? $shippingAddress : new Address(
				$this->faker->name,
				$this->faker->streetAddress,
				'',
				$this->faker->city,
				$this->faker->postcode,
				null,
				$country
			), $this->faker->boolean() ? null : 'CZ' . $this->faker->randomNumber(8), $this->faker->boolean() ? null : (string) $this->faker->randomNumber(8)),
			$paymentMethod,
			null,
			$items
		);
	}


	private function load(string $type, array $filter = []): void
	{
		$this->entities[$type] = $this->orm->getRepositoryForEntity($type)->findBy($filter)->fetchAll();
	}


	private function add(Transaction $transaction, Entity $entity): void
	{
		$transaction->persist($entity);
		$classes = array_merge([get_class($entity)], class_parents($entity));
		foreach ($classes as $class) {
			$this->entities[$class][] = $entity;
		}
	}


	private function random(string $type): Entity
	{
		assert(isset($this->entities[$type]));
		return $this->faker->randomElement($this->entities[$type]);
	}


	public function getDateTimeImmutableBetween($start, $end): \DateTimeImmutable
	{
		return \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween(
			\Nette\Utils\DateTime::from($start),
			\Nette\Utils\DateTime::from($end)
		));
	}
}
