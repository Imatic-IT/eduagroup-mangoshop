<?php declare(strict_types = 1);

namespace MangoShop\Address\Api;

use MangoShop\Address\Model\CountriesRepository;
use MangoShop\Address\Model\Country;
use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use Nextras\Orm\Collection\ICollection;


class CountryFacade
{
	/** @var CountriesRepository */
	private $countriesRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(CountriesRepository $countriesRepository, TransactionManager $transactionManager)
	{
		$this->countriesRepository = $countriesRepository;
		$this->transactionManager = $transactionManager;
	}


	/**
	 * @return ICollection|Country[]
	 */
	public function findAll(): ICollection
	{
		return $this->countriesRepository->findAll();
	}


	public function getById(int $countryId): Country
	{
		$country = $this->countriesRepository->getById($countryId);

		if ($country === null) {
			throw new EntityNotFoundException(Country::class, $countryId);
		}

		return $country;
	}


	public function getByCode(string $countryCode): Country
	{
		$country = $this->countriesRepository->getBy(['code' => $countryCode]);

		if ($country === null) {
			throw new EntityNotFoundException(Country::class);
		}

		return $country;
	}
}
