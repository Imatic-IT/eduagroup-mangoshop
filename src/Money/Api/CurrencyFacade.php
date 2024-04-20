<?php declare(strict_types = 1);

namespace MangoShop\Money\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Money\Model\CurrenciesRepository;
use MangoShop\Money\Model\Currency;


class CurrencyFacade
{
	/** @var CurrenciesRepository */
	private $currenciesRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(CurrenciesRepository $currenciesRepository, TransactionManager $transactionManager)
	{
		$this->currenciesRepository = $currenciesRepository;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $currencyId): Currency
	{
		$currency = $this->currenciesRepository->getById($currencyId);

		if ($currency === null) {
			throw new EntityNotFoundException(Currency::class, $currencyId);
		}

		return $currency;
	}


	public function getByCode(string $currencyCode): Currency
	{
		$currency = $this->currenciesRepository->getBy(['code' => $currencyCode]);

		if ($currency === null) {
			throw new EntityNotFoundException(Currency::class);
		}

		return $currency;
	}


	public function create(string $currencyCode): Currency
	{
		$transaction = $this->transactionManager->begin();

		$currency = new Currency($currencyCode);
		$transaction->persist($currency);

		$this->transactionManager->flush($transaction);

		return $currency;
	}
}
