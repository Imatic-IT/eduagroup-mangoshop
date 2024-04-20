<?php declare(strict_types = 1);

namespace MangoShopTests\Address\Inc;

use MangoShop\Address\Model\Address;
use MangoShop\Address\Model\Country;
use MangoShop\Address\Model\CountryState;
use MangoShopTests\EntityFactory;
use MangoShopTests\EntityGenerator;

class AddressEntityFactory extends EntityFactory
{
	public function createCountry(array $data): Country
	{
		static $codes = ['CZ', 'SK', 'US', 'GB'];

		$this->verifyData(['code'], $data);

		$code = $data['code'] ?? current($codes);
		next($codes);

		return new Country($code);
	}


	public function createCountryState(array $data, EntityGenerator $entityGenerator): CountryState
	{
		$this->verifyData(['country'], $data);

		$country = $entityGenerator->maybeCreate(Country::class, $data['country'] ?? []);

		return new CountryState($country, null, $this->counter(CountryState::class, 'State '));
	}


	public function createAddress(array $data, EntityGenerator $generator): Address
	{
		$this->verifyData(['recipientName', 'country', 'state', 'line1', 'city', 'postalCode'], $data);

		$country = $generator->maybeCreate(Country::class, $data['country'] ?? []);
		$state = $generator->maybeCreate(CountryState::class, $data['state'] ?? ['country' => $country]);
		$recipientName = $data['recipientName'] ?? 'John Doe';
		$line1 = $data['line1'] ?? 'Jungmannova 34';
		$city = $data['city'] ?? 'Praha 1';
		$postalCode = $data['postalCode'] ?? '11000';

		return new Address($recipientName, $line1, '', $city, $postalCode, $state, $country);
	}
}
