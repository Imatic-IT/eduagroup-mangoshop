<?php declare(strict_types = 1);

namespace MangoShop\Channel\Model;

use MangoShop\Core\NextrasOrm\Mapper;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Mapper\Dbal\DbalMapper;


class ChannelsMapper extends Mapper
{
	public function getManyHasManyParameters(PropertyMetadata $sourceProperty, DbalMapper $targetMapper)
	{
		if ($sourceProperty->name === 'locales') {
			return [
				'channel_locales',
				['channel_id', 'locale_id'],
			];
		}

		return parent::getManyHasManyParameters($sourceProperty, $targetMapper);
	}
}
