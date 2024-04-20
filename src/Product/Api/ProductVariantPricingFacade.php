<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Core\NextrasOrm\TransactionManager;


class ProductVariantPricingFacade
{
	/** @var ProductFacade */
	private $productFacade;

	/** @var ProductVariantFacade */
	private $productVariantFacade;

	/** @var ProductPricingGroupFacade */
	private $productPricingGroupFacade;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(
		ProductFacade $productFacade,
		ProductVariantFacade $productVariantFacade,
		ProductPricingGroupFacade $productPricingGroupFacade,
		TransactionManager $transactionManager
	) {
		$this->productFacade = $productFacade;
		$this->productVariantFacade = $productVariantFacade;
		$this->productPricingGroupFacade = $productPricingGroupFacade;
		$this->transactionManager = $transactionManager;
	}


	public function createOrUpdateProductPricing(int $productId, int $pricingGroupId, ProductVariantPricingStruct $data): void
	{
		$transaction = $this->transactionManager->begin();

		$product = $this->productFacade->getById($productId);
		$pricingGroup = $this->productPricingGroupFacade->getById($pricingGroupId);

		foreach ($product->variants as $productVariant) {
			$pricing = $pricingGroup->setPricingFor($productVariant, $data->priceCent, $data->originalPriceCents);
			$transaction->persist($pricing->previousVersion);
			$transaction->persist($pricing);
		}

		$this->transactionManager->flush($transaction);
	}


	public function createOrUpdateProductVariantPricing(int $productVariantId, int $pricingGroupId, ProductVariantPricingStruct $data): void
	{
		$transaction = $this->transactionManager->begin();

		$productVariant = $this->productVariantFacade->getById($productVariantId);
		$pricingGroup = $this->productPricingGroupFacade->getById($pricingGroupId);

		$pricing = $pricingGroup->setPricingFor($productVariant, $data->priceCent, $data->originalPriceCents);
		$transaction->persist($pricing->previousVersion);
		$transaction->persist($pricing);

		$this->transactionManager->flush($transaction);
	}
}
