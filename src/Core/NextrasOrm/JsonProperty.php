<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nette\Utils\Json;


class JsonProperty extends ImmutableValuePropertyContainer
{
	public function deserialize($value)
	{
		assert(is_string($value));
		return Json::decode($value, Json::FORCE_ARRAY);
	}


	public function serialize($value)
	{
		return Json::encode($value);
	}


	protected function isModified($oldValue, $newValue): bool
	{
		return $this->serialize($oldValue) !== $this->serialize($newValue);
	}
}
