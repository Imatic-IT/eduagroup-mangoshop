<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Product\Model\Product;
use MangoShop\Product\Model\ProductsRepository;
use MangoShop\Product\Model\ProductVariant;


class ProductFacade
{
	/** @var ProductsRepository */
	private $productsRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(
		ProductsRepository $productsRepository,
		TransactionManager $transactionManager
	) {
		$this->productsRepository = $productsRepository;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $productId): Product
	{
		$product = $this->productsRepository->getById($productId);

		if ($product === null) {
			throw new EntityNotFoundException(Product::class, $productId);
		}

		return $product;
	}


	public function getByCode(string $productCode): Product
	{
		$product = $this->productsRepository->getBy(['code' => $productCode]);

		if ($product === null) {
			throw new EntityNotFoundException(Product::class);
		}

		return $product;
	}


	public function create(string $productCode, ProductStruct $data): Product
	{
		$transaction = $this->transactionManager->begin();

		$product = new Product($productCode);
		$product->setEnabled($data->enabled);

		foreach ($data->variants as $productVariantCode => $productVariantData) {
			$productVariant = new ProductVariant($productVariantCode, $product);
			$productVariant->setEnabled($productVariantData->enabled);
			assert($product->hasVariant($productVariant));
		}

		$transaction->persist($product);

		$this->transactionManager->flush($transaction);

		return $product;
	}


	public function update(int $productId, ProductStruct $data): void
	{
		$transaction = $this->transactionManager->begin();

		$product = $this->getById($productId);
		$product->setEnabled($data->enabled);

		// product variants cannot be deleted, only disabled
		$existingVariants = [];
		foreach ($product->variants as $productVariant) {
			assert(isset($data->variants[$productVariant->code]));
			$existingVariants[$productVariant->code] = $productVariant;
		}

		// create or update existing
		foreach ($data->variants as $productVariantCode => $productVariantData) {
			if (isset($existingVariants[$productVariantCode])) {
				$productVariant = $existingVariants[$productVariantCode];

			} else {
				$productVariant = new ProductVariant($productVariantCode, $product);
				assert($product->hasVariant($productVariant));
			}

			$productVariant->setEnabled($productVariantData->enabled);
		}

		$transaction->persist($product);

		$this->transactionManager->flush($transaction);
	}
}
