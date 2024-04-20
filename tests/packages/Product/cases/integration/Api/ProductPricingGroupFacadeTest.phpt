<?php declare(strict_types = 1);

namespace MangoShopTests\Product\Cases\Integration\Api;

use MangoShop\Money\Model\Currency;
use MangoShop\Product\Api\ProductPricingGroupFacade;
use MangoShop\Product\Api\ProductPricingGroupStruct;
use MangoShop\Product\Model\ProductPricingGroup;
use Mangoweb\Tester\Infrastructure\TestCase;
use MangoShopTests\EntityGenerator;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class ProductPricingGroupFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testGetById(ProductPricingGroupFacade $facade)
	{
		$pricingGroupId = $this->entityGenerator->create(ProductPricingGroup::class)->id;
		$pricingGroup = $facade->getById($pricingGroupId);
		Assert::type(ProductPricingGroup::class, $pricingGroup);
	}


	public function testCreate(ProductPricingGroupFacade $facade)
	{
		$currencyId = $this->entityGenerator->create(Currency::class)->id;

		$pricingGroup = $facade->create($currencyId, new ProductPricingGroupStruct('My Pricing Group'));
		Assert::same('My Pricing Group', $pricingGroup->name);
		Assert::same($currencyId, $pricingGroup->currency->id);
	}


	public function testUpdate(ProductPricingGroupFacade $facade)
	{
		$pricingGroup = $this->entityGenerator->create(ProductPricingGroup::class);

		$facade->update($pricingGroup->id, new ProductPricingGroupStruct('Update Name'));
		$this->entityGenerator->refreshAll();

		Assert::same('Update Name', $pricingGroup->name);
	}
}


ProductPricingGroupFacadeTest::run($containerFactory);
