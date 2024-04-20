<?php declare(strict_types = 1);

namespace MangoShop\Product\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Money\Api\CurrencyFacade;
use MangoShop\Product\Model\ProductPricingGroup;
use MangoShop\Product\Model\ProductPricingGroupsRepository;
use Nextras\Orm\Collection\ICollection;


class ProductPricingGroupFacade
{
	/** @var ProductPricingGroupsRepository */
	private $pricingGroupsRepository;

	/** @var CurrencyFacade */
	private $currencyFacade;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(
		ProductPricingGroupsRepository $pricingGroupsRepository,
		CurrencyFacade $currencyFacade,
		TransactionManager $transactionManager
	) {
		$this->pricingGroupsRepository = $pricingGroupsRepository;
		$this->currencyFacade = $currencyFacade;
		$this->transactionManager = $transactionManager;
	}


	/**
	 * @return ICollection|ProductPricingGroup[]
	 */
	public function findAll(): ICollection
	{
		return $this->pricingGroupsRepository->findAll();
	}


	public function getById(int $pricingGroupId): ProductPricingGroup
	{
		$pricingGroup = $this->pricingGroupsRepository->getById($pricingGroupId);

		if ($pricingGroup === null) {
			throw new EntityNotFoundException(ProductPricingGroup::class, $pricingGroupId);
		}

		return $pricingGroup;
	}


	public function create(int $currencyId, ProductPricingGroupStruct $data): ProductPricingGroup
	{
		$transaction = $this->transactionManager->begin();

		$currency = $this->currencyFacade->getById($currencyId);
		$productPricingGroup = new ProductPricingGroup($data->name, $currency);

		$transaction->persist($productPricingGroup);

		$this->transactionManager->flush($transaction);

		return $productPricingGroup;
	}


	public function update(int $pricingGroupId, ProductPricingGroupStruct $data): void
	{
		$transaction = $this->transactionManager->begin();

		$productPricingGroup = $this->getById($pricingGroupId);
		$productPricingGroup->setName($data->name);

		$transaction->persist($productPricingGroup);

		$this->transactionManager->flush($transaction);
	}
}
