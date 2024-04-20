<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nextras\Dbal\IConnection;
use Nextras\Orm\Model\IModel;


class Transaction
{
	/** @var IModel */
	private $orm;

	/** @var IConnection */
	private $connection;

	/** @var Transaction|null */
	private $parentTransaction;


	public function __construct(IModel $orm, IConnection $connection, ?self $parentTransaction)
	{
		$this->orm = $orm;
		$this->parentTransaction = $parentTransaction;
		$this->connection = $connection;
	}


	public function hasParentTransaction(): bool
	{
		return $this->parentTransaction !== null;
	}


	public function persist(?Entity ...$entities): void
	{
		foreach ($entities as $entity) {
			if ($entity !== null) {
				$this->orm->persist($entity);
			}
		}
	}


	public function persistWithoutCascade(?Entity ...$entities): void
	{
		foreach ($entities as $entity) {
			if ($entity !== null) {
				$this->orm->persist($entity, false);
			}
		}
	}


	public function remove(?Entity ...$entities): void
	{
		foreach ($entities as $entity) {
			if ($entity !== null) {
				$this->orm->remove($entity);
			}
		}
	}


	public function removeWithoutCascade(?Entity ...$entities): void
	{
		foreach ($entities as $entity) {
			if ($entity !== null) {
				$this->orm->remove($entity, false);
			}
		}
	}
}
