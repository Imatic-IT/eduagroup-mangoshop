<?php declare(strict_types = 1);

namespace MangoShop\Core\NextrasOrm;

use Nextras\Dbal\IConnection;
use Nextras\Orm\Model\IModel;


class TransactionManager
{
	/** @var IModel */
	private $orm;

	/** @var Transaction[] */
	private $transactions = [];

	/** @var IConnection */
	private $connection;


	public function __construct(IModel $orm, IConnection $connection)
	{
		$this->orm = $orm;
		$this->connection = $connection;
	}


	public function begin(): Transaction
	{
		if (count($this->transactions) === 0) {
			$this->connection->beginTransaction();
		}
		return $this->transactions[] = new Transaction($this->orm, $this->connection, end($this->transactions) ?: null);
	}


	public function flush(Transaction $transaction): void
	{
		assert(array_pop($this->transactions) === $transaction);
		if (count($this->transactions) === 0) {
			$this->orm->flush();
			$this->connection->commitTransaction();
		}
	}


	public function transactional(callable $callable)
	{
		$flushed = false;
		try {
			$transaction = $this->begin();
			$result = $callable($transaction);
			$this->flush($transaction);
			$flushed = true;

			return $result;

		} finally {
			if (!$flushed) {
				array_pop($this->transactions);
			}
		}
	}
}
