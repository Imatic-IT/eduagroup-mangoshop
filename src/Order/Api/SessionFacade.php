<?php declare(strict_types = 1);

namespace MangoShop\Order\Api;

use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Order\Model\Session;

class SessionFacade
{
	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(TransactionManager $transactionManager)
	{
		$this->transactionManager = $transactionManager;
	}


	public function createSessionToken(): string
	{
		$transaction = $this->transactionManager->begin();
		$session = new Session();
		$transaction->persist($session);
		$this->transactionManager->flush($transaction);

		return $session->token;
	}
}
