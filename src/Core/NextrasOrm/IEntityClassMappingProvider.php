<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;


interface IEntityClassMappingProvider
{
	/**
	 * @return array<string, string[]> repository => array of entity class names
	 */
	public function getEntityClassNames(): array;
}
