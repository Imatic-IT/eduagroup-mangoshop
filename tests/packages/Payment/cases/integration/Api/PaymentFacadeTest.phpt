<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Integration\Api;

use MangoShop\Locale\Model\Locale;
use MangoShop\Money\Model\Currency;
use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShopTests\EntityGenerator;
use MangoShopTests\Payment\Inc\DummyExternalStateCodeEnum;
use MangoShopTests\Payment\Inc\PaymentEntityFactory;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class PaymentFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testGetById(PaymentFacade $facade)
	{
		$paymentId = $this->entityGenerator->create(Payment::class)->id;
		$payment = $facade->getById($paymentId);
		Assert::type(Payment::class, $payment);
	}


	public function testGetByExternalIdentifier(PaymentFacade $facade)
	{
		$payment = $this->entityGenerator->create(Payment::class, PaymentEntityFactory::createInitializedPayment());
		$fetchedPayment = $facade->getByExternalIdentifier($payment->paymentMethod->id, $payment->externalIdentifier);
		Assert::type(Payment::class, $fetchedPayment);
	}


	public function testFindAll(PaymentFacade $facade)
	{
		Assert::count(0, $facade->findAll());
		$this->entityGenerator->create(Payment::class);
		Assert::count(1, $facade->findAll());
	}


	public function testCreateAndInitialize(PaymentFacade $facade)
	{
		$paymentMethod = $this->entityGenerator->create(PaymentMethod::class, ['code' => '__dummy']);
		$currency = $this->entityGenerator->create(Currency::class);
		$locale = $this->entityGenerator->create(Locale::class);

		$payment = $facade->create($paymentMethod->id, $currency->id, 12345, $locale->id);
		Assert::type(Payment::class, $payment);
		Assert::null($payment->externalIdentifier);
		Assert::null($payment->state->externalState);

		$response = $facade->initialize($payment->id, new PaymentInitializationRequest(null, null, []));
		Assert::same('dummy#1', $response->getExternalIdentifier());
		Assert::same($response->getExternalIdentifier(), $payment->externalIdentifier);
		Assert::same(DummyExternalStateCodeEnum::CREATED(), $payment->state->externalState->getCode());
	}
}


PaymentFacadeTest::run($containerFactory);
