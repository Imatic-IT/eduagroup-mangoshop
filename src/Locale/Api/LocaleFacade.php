<?php declare(strict_types = 1);

namespace MangoShop\Locale\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Locale\Model\Locale;
use MangoShop\Locale\Model\LocalesRepository;


class LocaleFacade
{
	/** @var LocalesRepository */
	private $localesRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(LocalesRepository $localesRepository, TransactionManager $transactionManager)
	{
		$this->localesRepository = $localesRepository;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $localeId): Locale
	{
		$locale = $this->localesRepository->getById($localeId);

		if ($locale === null) {
			throw new EntityNotFoundException(Locale::class, $localeId);
		}

		return $locale;
	}


	public function getByCode(string $localeCode): Locale
	{
		$locale = $this->localesRepository->getBy(['code' => $localeCode]);

		if ($locale === null) {
			throw new EntityNotFoundException(Locale::class);
		}

		return $locale;
	}


	public function create(string $localeCode): Locale
	{
		$transaction = $this->transactionManager->begin();

		$locale = new Locale($localeCode);
		$transaction->persist($locale);

		$this->transactionManager->flush($transaction);

		return $locale;
	}
}
