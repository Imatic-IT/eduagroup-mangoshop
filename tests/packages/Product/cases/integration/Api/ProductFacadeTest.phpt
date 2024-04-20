<?php declare(strict_types = 1);

namespace MangoShopTests\Product\Cases\Integration\Api;

use MangoShop;
use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Product\Api\ProductFacade;
use MangoShop\Product\Api\ProductStruct;
use MangoShop\Product\Api\ProductVariantStruct;
use MangoShop\Product\Model\Product;
use Mangoweb\Tester\Infrastructure\TestCase;
use MangoShopTests\EntityGenerator;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../../../bootstrap.php';


/**
 * @testCase
 */
class ProductFacadeTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testGetByIdOk(ProductFacade $facade)
	{
		$productId = $this->entityGenerator->create(Product::class)->id;
		$product = $facade->getById($productId);
		Assert::type(Product::class, $product);
	}


	public function testGetByIdNotFound(ProductFacade $facade)
	{
		Assert::exception(
			function () use ($facade) {
				$facade->getById(123);
			},
			EntityNotFoundException::class
		);
	}


	public function testGetByCodeOk(ProductFacade $facade)
	{
		$productCode = $this->entityGenerator->create(Product::class)->code;
		$product = $facade->getByCode($productCode);
		Assert::type(Product::class, $product);
	}


	public function testCreateOk(ProductFacade $productFacade)
	{
		$product = $productFacade->create(
			'ABC',
			new ProductStruct(
				false,
				[
					'ABC-XYZ-001' => new ProductVariantStruct(false),
					'ABC-XYZ-002' => new ProductVariantStruct(true),
				]
			)
		);

		Assert::false($product->enabled);
		Assert::count(2, $product->variants);
		Assert::false($product->variants->fetchAll()[0]->enabled);
		Assert::true($product->variants->fetchAll()[1]->enabled);
	}


	public function testUpdateOk(ProductFacade $productFacade)
	{
		$product = $this->entityGenerator->create(Product::class);

		$productStructA = ProductStruct::createFromProduct($product);
		$productStructA->enabled = false;

		$productFacade->update($product->id, $productStructA);
		$this->entityGenerator->refreshAll();
		Assert::false($product->enabled);

		$productStructB = ProductStruct::createFromProduct($product);
		$productStructB->enabled = true;

		$productFacade->update($product->id, $productStructB);
		$this->entityGenerator->refreshAll();
		Assert::true($product->enabled);
	}
}


ProductFacadeTest::run($containerFactory);
