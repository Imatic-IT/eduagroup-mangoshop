<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Mapper\IRelationshipMapper;


abstract class CollectionWrapper implements ICollection
{
	/** @var ICollection */
	protected $innerCollection;


	public function __construct(ICollection $collection)
	{
		$this->innerCollection = $collection;
	}


	public function getBy(array $where): ?IEntity
	{
		return $this->innerCollection->getBy($where);
	}


	public function getById($id): ?IEntity
	{
		return $this->innerCollection->getById($id);
	}


	/**
	 * @return static
	 */
	public function findBy(array $where): ICollection
	{
		return new static($this->innerCollection->findBy($where));
	}


	/**
	 * @return static
	 */
	public function orderBy($column, string $direction = self::ASC): ICollection
	{
		return new static($this->innerCollection->orderBy($column, $direction));
	}


	/**
	 * @return static
	 */
	public function resetOrderBy(): ICollection
	{
		return new static($this->innerCollection->resetOrderBy());
	}


	/**
	 * @return static
	 */
	public function limitBy(int $limit, int $offset = null): ICollection
	{
		return new static($this->innerCollection->limitBy($limit, $offset));
	}


	/**
	 * @return static
	 */
	public function applyFunction(string $functionName, ...$args): ICollection
	{
		return new static($this->innerCollection->applyFunction($functionName, ...$args));
	}


	public function fetch(): ?IEntity
	{
		return $this->innerCollection->fetch();
	}


	public function fetchAll(): array
	{
		return $this->innerCollection->fetchAll();
	}


	public function fetchPairs(string $key = null, string $value = null): array
	{
		return $this->innerCollection->fetchPairs($key, $value);
	}


	/**
	 * @return static
	 */
	public function setRelationshipMapper(IRelationshipMapper $mapper = null): ICollection
	{
		$this->innerCollection->setRelationshipMapper($mapper);
		return $this;
	}


	public function getRelationshipMapper(): ?IRelationshipMapper
	{
		return $this->innerCollection->getRelationshipMapper();
	}


	/**
	 * @return static
	 */
	public function setRelationshipParent(IEntity $parent): ICollection
	{
		return new static($this->innerCollection->setRelationshipParent($parent));
	}


	public function countStored(): int
	{
		return $this->innerCollection->countStored();
	}


	public function subscribeOnEntityFetch(callable $callback): void
	{
		$this->innerCollection->subscribeOnEntityFetch($callback);
	}


	public function getIterator(): \Traversable
	{
		return $this->innerCollection->getIterator();
	}


	public function count(): int
	{
		return $this->innerCollection->count();
	}
}
