<?php declare(strict_types = 1);

namespace MangoShop\Payment\Api;

use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Locale\Api\LocaleFacade;
use MangoShop\Money\Api\CurrencyFacade;
use MangoShop\Money\Model\Money;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentDriverProvider;
use MangoShop\Payment\Model\PaymentInitializationRequest;
use MangoShop\Payment\Model\PaymentInitializationResponse;
use MangoShop\Payment\Model\PaymentService;
use MangoShop\Payment\Model\PaymentsRepository;
use Nextras\Orm\Collection\ICollection;


class PaymentFacade
{
	/** @var PaymentsRepository */
	private $paymentsRepository;

	/** @var PaymentMethodFacade */
	private $paymentMethodFacade;

	/** @var CurrencyFacade */
	private $currencyFacade;

	/** @var LocaleFacade */
	private $localeFacade;

	/** @var PaymentDriverProvider */
	private $paymentDriverProvider;

	/** @var PaymentService */
	private $paymentService;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(
		PaymentsRepository $paymentsRepository,
		PaymentMethodFacade $paymentMethodFacade,
		CurrencyFacade $currencyFacade,
		LocaleFacade $localeFacade,
		PaymentDriverProvider $paymentDriverProvider,
		PaymentService $paymentService,
		TransactionManager $transactionManager
	) {
		$this->paymentsRepository = $paymentsRepository;
		$this->paymentMethodFacade = $paymentMethodFacade;
		$this->currencyFacade = $currencyFacade;
		$this->localeFacade = $localeFacade;
		$this->paymentDriverProvider = $paymentDriverProvider;
		$this->paymentService = $paymentService;
		$this->transactionManager = $transactionManager;
	}


	public function getById(int $paymentId): Payment
	{
		$payment = $this->paymentsRepository->getById($paymentId);

		if ($payment === null) {
			throw new EntityNotFoundException(Payment::class, $paymentId);
		}

		return $payment;
	}


	public function getByExternalIdentifier(int $paymentMethodId, string $externalIdentifier): Payment
	{
		$payment = $this->paymentsRepository->getBy([
			'paymentMethod' => $paymentMethodId,
			'externalIdentifier' => $externalIdentifier,
		]);

		if ($payment === null) {
			throw new EntityNotFoundException(Payment::class);
		}

		return $payment;
	}


	/**
	 * @return Payment[]|ICollection
	 */
	public function findAll(): ICollection
	{
		return $this->paymentsRepository->findAll();
	}


	public function create(int $paymentMethodId, int $currencyId, int $amountCents, int $localeId): Payment
	{
		$transaction = $this->transactionManager->begin();

		$paymentMethod = $this->paymentMethodFacade->getById($paymentMethodId);
		$currency = $this->currencyFacade->getById($currencyId);
		$locale = $this->localeFacade->getById($localeId);

		$payment = new Payment($paymentMethod, new Money($amountCents, $currency), $locale);
		$transaction->persist($payment);

		$this->transactionManager->flush($transaction);

		return $payment;
	}


	public function initialize(int $paymentId, PaymentInitializationRequest $data): PaymentInitializationResponse
	{
		$transaction = $this->transactionManager->begin();

		$payment = $this->getById($paymentId);
		$paymentDriver = $this->paymentDriverProvider->getPaymentDriver($payment);
		$response = $paymentDriver->initialize($payment, $data);

		$this->paymentService->initializeExternalState(
			$transaction,
			$payment,
			$response->getExternalIdentifier(),
			$response->getExternalState()
		);

		$this->transactionManager->flush($transaction);

		return $response;
	}


	public function refund(int $paymentId): Payment
	{
		$transaction = $this->transactionManager->begin();

		$payment = $this->getById($paymentId);
		$paymentDriver = $this->paymentDriverProvider->getPaymentDriver($payment);
		$externalState = $paymentDriver->refund($payment);

		$this->paymentService->advanceByExternalState(
			$transaction,
			$payment,
			$externalState
		);

		$this->transactionManager->flush($transaction);

		return $payment;
	}
}
