<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Mapper\Mapper as BaseMapper;


abstract class Mapper extends BaseMapper
{
	public function findAll(): ICollection
	{
		return $this->wrapCollection(parent::findAll());
	}


	public function toCollection($data): ICollection
	{
		return $this->wrapCollection(parent::toCollection($data));
	}


	protected function wrapCollection(ICollection $collection): ICollection
	{
		return $collection;
	}
}
