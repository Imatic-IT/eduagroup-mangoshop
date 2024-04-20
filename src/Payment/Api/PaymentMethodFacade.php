<?php declare(strict_types = 1);

namespace MangoShop\Payment\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Payment\Model\PaymentMethod;
use MangoShop\Payment\Model\PaymentMethodsRepository;
use Nextras\Orm\Collection\ICollection;


class PaymentMethodFacade
{
	/** @var PaymentMethodsRepository */
	private $paymentMethodsRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(PaymentMethodsRepository $paymentMethodsRepository, TransactionManager $transactionManager)
	{
		$this->paymentMethodsRepository = $paymentMethodsRepository;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $paymentMethodId): PaymentMethod
	{
		$paymentMethod = $this->paymentMethodsRepository->getById($paymentMethodId);

		if ($paymentMethod === null) {
			throw new EntityNotFoundException(PaymentMethod::class, $paymentMethodId);
		}

		return $paymentMethod;
	}


	public function getByCode(string $paymentMethodCode): PaymentMethod
	{
		$paymentMethod = $this->paymentMethodsRepository->getBy(['code' => $paymentMethodCode]);

		if ($paymentMethod === null) {
			throw new EntityNotFoundException(PaymentMethod::class);
		}

		return $paymentMethod;
	}


	/**
	 * @return PaymentMethod[]|ICollection
	 */
	public function findAll(): ICollection
	{
		return $this->paymentMethodsRepository->findAll();
	}


	public function update(int $paymentMethodId, bool $enabled): void
	{
		$transaction = $this->transactionManager->begin();

		$paymentMethod = $this->getById($paymentMethodId);
		$paymentMethod->setEnabled($enabled);
		$transaction->persist($paymentMethod);

		$this->transactionManager->flush($transaction);
	}
}
