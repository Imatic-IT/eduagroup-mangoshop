<?php declare(strict_types = 1);

namespace MangoShopTests\Product\Cases\Integration\Api;

use MangoShop\Product\Api\ProductVariantPricingFacade;
use MangoShop\Product\Api\ProductVariantPricingStruct;
use MangoShop\Product\Model\Product;
use MangoShop\Product\Model\ProductPricingGroup;
use MangoShop\Product\Model\ProductVariant;
use Mangoweb\Tester\Infrastructure\TestCase;
use MangoShopTests\EntityGenerator;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class ProductVariantPricingFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testCreateOrUpdateProductPricing(ProductVariantPricingFacade $facade)
	{
		$product = $this->entityGenerator->create(Product::class, ['variants' => 3]);
		$pricingGroup = $this->entityGenerator->create(ProductPricingGroup::class);

		/** @var ProductVariant[] $variants */
		$variants = $product->variants->fetchAll();

		Assert::null($pricingGroup->getPricingFor($variants[0]));
		Assert::null($pricingGroup->getPricingFor($variants[1]));
		Assert::null($pricingGroup->getPricingFor($variants[2]));

		$facade->createOrUpdateProductPricing($product->id, $pricingGroup->id, new ProductVariantPricingStruct(12345, 98765));
		$this->entityGenerator->refreshAll();

		Assert::same(12345, $pricingGroup->getPricingFor($variants[0])->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[0])->originalPriceCents);

		Assert::same(12345, $pricingGroup->getPricingFor($variants[1])->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[1])->originalPriceCents);

		Assert::same(12345, $pricingGroup->getPricingFor($variants[2])->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[2])->originalPriceCents);

		$facade->createOrUpdateProductPricing($product->id, $pricingGroup->id, new ProductVariantPricingStruct(77777, null));
		$this->entityGenerator->refreshAll();

		Assert::same(77777, $pricingGroup->getPricingFor($variants[0])->priceCents);
		Assert::null($pricingGroup->getPricingFor($variants[0])->originalPriceCents);

		Assert::same(77777, $pricingGroup->getPricingFor($variants[1])->priceCents);
		Assert::null($pricingGroup->getPricingFor($variants[1])->originalPriceCents);

		Assert::same(77777, $pricingGroup->getPricingFor($variants[2])->priceCents);
		Assert::null($pricingGroup->getPricingFor($variants[2])->originalPriceCents);

		Assert::same(12345, $pricingGroup->getPricingFor($variants[0])->previousVersion->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[0])->previousVersion->originalPriceCents);

		Assert::same(12345, $pricingGroup->getPricingFor($variants[1])->previousVersion->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[1])->previousVersion->originalPriceCents);

		Assert::same(12345, $pricingGroup->getPricingFor($variants[2])->previousVersion->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[2])->previousVersion->originalPriceCents);
	}


	public function testCreateOrUpdateProductVariantPricing(ProductVariantPricingFacade $facade)
	{
		$product = $this->entityGenerator->create(Product::class, ['variants' => 3]);
		$pricingGroup = $this->entityGenerator->create(ProductPricingGroup::class);

		/** @var ProductVariant[] $variants */
		$variants = $product->variants->fetchAll();

		Assert::null($pricingGroup->getPricingFor($variants[0]));
		Assert::null($pricingGroup->getPricingFor($variants[1]));
		Assert::null($pricingGroup->getPricingFor($variants[2]));

		$facade->createOrUpdateProductVariantPricing($variants[2]->id, $pricingGroup->id, new ProductVariantPricingStruct(12345, 98765));
		$this->entityGenerator->refreshAll();

		Assert::null($pricingGroup->getPricingFor($variants[0]));
		Assert::null($pricingGroup->getPricingFor($variants[1]));

		Assert::same(12345, $pricingGroup->getPricingFor($variants[2])->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[2])->originalPriceCents);

		$facade->createOrUpdateProductVariantPricing($variants[2]->id, $pricingGroup->id, new ProductVariantPricingStruct(77777, null));
		$this->entityGenerator->refreshAll();

		Assert::null($pricingGroup->getPricingFor($variants[0]));
		Assert::null($pricingGroup->getPricingFor($variants[1]));

		Assert::same(77777, $pricingGroup->getPricingFor($variants[2])->priceCents);
		Assert::null($pricingGroup->getPricingFor($variants[2])->originalPriceCents);

		Assert::same(12345, $pricingGroup->getPricingFor($variants[2])->previousVersion->priceCents);
		Assert::same(98765, $pricingGroup->getPricingFor($variants[2])->previousVersion->originalPriceCents);
	}
}


ProductVariantPricingFacadeTest::run($containerFactory);
