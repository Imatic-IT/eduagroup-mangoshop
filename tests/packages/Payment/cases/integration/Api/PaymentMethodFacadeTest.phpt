<?php declare(strict_types = 1);

namespace MangoShopTests\Payment\Cases\Integration\Api;

use MangoShop;
use MangoShop\Payment\Api\PaymentMethodFacade;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShopTests\EntityGenerator;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class PaymentMethodFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testGetById(PaymentMethodFacade $facade)
	{
		$paymentMethodId = $this->entityGenerator->create(PaymentMethod::class)->id;
		$paymentMethod = $facade->getById($paymentMethodId);
		Assert::type(PaymentMethod::class, $paymentMethod);
	}


	public function testGetByCode(PaymentMethodFacade $facade)
	{
		$paymentMethodCode = $this->entityGenerator->create(PaymentMethod::class)->code;
		$paymentMethod = $facade->getByCode($paymentMethodCode);
		Assert::type(PaymentMethod::class, $paymentMethod);
	}


	public function testFindAll(PaymentMethodFacade $facade)
	{
		Assert::count(0, $facade->findAll());
		$this->entityGenerator->create(PaymentMethod::class);
		$this->entityGenerator->create(PaymentMethod::class);
		Assert::count(2, $facade->findAll());
	}


	public function testUpdate(PaymentMethodFacade $facade)
	{
		$paymentMethodId = $this->entityGenerator->create(PaymentMethod::class)->id;

		$facade->update($paymentMethodId, false);
		Assert::false($facade->getById($paymentMethodId)->enabled);

		$facade->update($paymentMethodId, true);
		Assert::true($facade->getById($paymentMethodId)->enabled);
	}
}


PaymentMethodFacadeTest::run($containerFactory);
