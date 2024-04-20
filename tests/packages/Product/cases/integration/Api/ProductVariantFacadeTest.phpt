<?php declare(strict_types = 1);

namespace MangoShopTests\Product\Cases\Integration\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Product\Api\ProductVariantFacade;
use MangoShop\Product\Api\ProductVariantStruct;
use MangoShop\Product\Model\Product;
use MangoShop\Product\Model\ProductVariant;
use Mangoweb\Tester\Infrastructure\TestCase;
use MangoShopTests\EntityGenerator;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class ProductVariantFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testGetByIdNotFound(ProductVariantFacade $facade)
	{
		Assert::exception(
			function () use ($facade) {
				$facade->getById(123);
			},
			EntityNotFoundException::class
		);
	}


	public function testGetByIdOk(ProductVariantFacade $facade)
	{
		$productVariantId = $this->entityGenerator->create(ProductVariant::class)->id;
		$productVariant = $facade->getById($productVariantId);
		Assert::type(ProductVariant::class, $productVariant);
	}


	public function testGetByCodeOk(ProductVariantFacade $facade)
	{
		$productVariantCode = $this->entityGenerator->create(ProductVariant::class)->code;
		$product = $facade->getByCode($productVariantCode);
		Assert::type(ProductVariant::class, $product);
	}


	public function testCreateVariantOk(ProductVariantFacade $facade)
	{
		$product = $this->entityGenerator->create(Product::class);
		Assert::count(1, $product->variants);

		$productVariant = $facade->create(
			$product->id,
			'ABC-XYZ-001',
			new ProductVariantStruct(false)
		);

		$this->entityGenerator->refreshAll();
		Assert::count(2, $product->variants);
		Assert::same($product->id, $productVariant->product->id);
		Assert::same('ABC-XYZ-001', $productVariant->code);
		Assert::false($productVariant->enabled);
	}


	public function testUpdateVariantOk(ProductVariantFacade $facade)
	{
		$product = $this->entityGenerator->create(Product::class);
		$productVariant = $product->variants->fetch();

		$facade->update(
			$productVariant->id,
			new ProductVariantStruct(false)
		);

		$this->entityGenerator->refreshAll();
		Assert::false($productVariant->enabled);
	}
}


ProductVariantFacadeTest::run($containerFactory);
