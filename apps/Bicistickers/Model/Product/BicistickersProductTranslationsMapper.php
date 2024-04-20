<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Product\Model\ProductTranslationsMapper;
use Nextras\Orm\Mapper\Dbal\StorageReflection\UnderscoredStorageReflection;


class BicistickersProductTranslationsMapper extends ProductTranslationsMapper
{
	public function getTableName(): string
	{
		return 'product_translations';
	}


	protected function createStorageReflection()
	{
		return new UnderscoredStorageReflection(
			$this->connection,
			'product_translations_original',
			$this->getRepository()->getEntityMetadata()->getPrimaryKey(),
			$this->cache
		);
	}
}
