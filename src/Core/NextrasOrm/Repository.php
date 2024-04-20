<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Repository\Repository as BaseRepository;


abstract class Repository extends BaseRepository
{
	/** @var string[][] */
	private static $classNames = [];


	public static function addEntityClass(string $repositoryClass, string $entityClass): void
	{
		assert(is_a($repositoryClass, self::class, true));
		assert(is_a($entityClass, IEntity::class, true));
		self::$classNames[$repositoryClass][] = $entityClass;
	}


	/**
	 * @return string[]
	 */
	protected static function getDefinedEntityClassNames(string $repositoryClass): array
	{
		return self::$classNames[$repositoryClass] ?? [];
	}
}
