<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Product\Model\ProductVariantTranslationsMapper;
use Nextras\Orm\Mapper\Dbal\StorageReflection\UnderscoredStorageReflection;


class BicistickersProductVariantTranslationsMapper extends ProductVariantTranslationsMapper
{
	public function getTableName(): string
	{
		return 'product_variant_translations';
	}


	protected function createStorageReflection()
	{
		return new UnderscoredStorageReflection(
			$this->connection,
			'product_variant_translations_original',
			$this->getRepository()->getEntityMetadata()->getPrimaryKey(),
			$this->cache
		);
	}
}
