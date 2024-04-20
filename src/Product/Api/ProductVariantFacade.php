<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Product\Model\ProductVariant;
use MangoShop\Product\Model\ProductVariantsRepository;


class ProductVariantFacade
{
	/** @var ProductVariantsRepository */
	private $productVariantsRepository;

	/** @var ProductFacade */
	private $productFacade;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(
		ProductVariantsRepository $productVariantsRepository,
		ProductFacade $productFacade,
		TransactionManager $transactionManager
	) {
		$this->productVariantsRepository = $productVariantsRepository;
		$this->productFacade = $productFacade;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $productVariantId): ProductVariant
	{
		$productVariant = $this->productVariantsRepository->getById($productVariantId);

		if ($productVariant === null) {
			throw new EntityNotFoundException(ProductVariant::class, $productVariantId);
		}

		return $productVariant;
	}


	public function getByCode(string $productVariantCode): ProductVariant
	{
		$productVariant = $this->productVariantsRepository->getBy(['code' => $productVariantCode]);

		if ($productVariant === null) {
			throw new EntityNotFoundException(ProductVariant::class);
		}

		return $productVariant;
	}


	public function create(int $productId, string $productVariantCode, ProductVariantStruct $data): ProductVariant
	{
		$transaction = $this->transactionManager->begin();

		$product = $this->productFacade->getById($productId);
		$productVariant = new ProductVariant($productVariantCode, $product);
		$productVariant->setEnabled($data->enabled);

		$transaction->persist($productVariant);

		$this->transactionManager->flush($transaction);

		return $productVariant;
	}


	public function update(int $productVariantId, ProductVariantStruct $data): void
	{
		$transaction = $this->transactionManager->begin();

		$productVariant = $this->getById($productVariantId);
		$productVariant->setEnabled($data->enabled);

		$transaction->persist($productVariant);

		$this->transactionManager->flush($transaction);
	}
}
