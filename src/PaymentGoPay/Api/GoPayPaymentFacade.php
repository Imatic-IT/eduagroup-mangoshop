<?php declare(strict_types = 1);

namespace MangoShop\PaymentGoPay\Api;

use MangoShop\Core\NextrasOrm\Transaction;
use MangoShop\Core\NextrasOrm\TransactionManager;
use MangoShop\Payment\Api\PaymentFacade;
use MangoShop\Payment\Api\PaymentMethodFacade;
use MangoShop\Payment\Model\Payment;
use MangoShop\Payment\Model\PaymentService;
use MangoShop\PaymentGoPay\Model\GoPayPaymentDriver;
use Psr\Log\LoggerInterface;


class GoPayPaymentFacade
{
	/** @var PaymentFacade */
	private $paymentFacade;

	/** @var PaymentMethodFacade */
	private $paymentMethodFacade;

	/** @var GoPayPaymentDriver */
	private $paymentDriver;

	/** @var PaymentService */
	private $paymentService;

	/** @var TransactionManager */
	private $transactionManager;

	/** @var LoggerInterface */
	private $logger;


	public function __construct(
		PaymentFacade $paymentFacade,
		PaymentMethodFacade $paymentMethodFacade,
		GoPayPaymentDriver $paymentDriver,
		PaymentService $paymentService,
		TransactionManager $transactionManager,
		LoggerInterface $logger
	) {
		$this->paymentFacade = $paymentFacade;
		$this->paymentMethodFacade = $paymentMethodFacade;
		$this->paymentDriver = $paymentDriver;
		$this->paymentService = $paymentService;
		$this->transactionManager = $transactionManager;
		$this->logger = $logger;
	}


	public function getByExternalIdentifier(string $externalIdentifier): Payment
	{
		$paymentMethodCode = $this->paymentDriver->getPaymentMethodCode();
		$paymentMethod = $this->paymentMethodFacade->getByCode($paymentMethodCode);
		$payment = $this->paymentFacade->getByExternalIdentifier($paymentMethod->id, $externalIdentifier);

		return $payment;
	}


	public function processGatewayReturn(string $externalIdentifier): Payment
	{
		$transaction = $this->transactionManager->begin();

		$payment = $this->doRefreshState($transaction, $externalIdentifier);

		$this->transactionManager->flush($transaction);

		return $payment;
	}


	public function processGatewayNotification(string $externalIdentifier): Payment
	{
		$transaction = $this->transactionManager->begin();

		$payment = $this->doRefreshState($transaction, $externalIdentifier);

		$this->transactionManager->flush($transaction);

		return $payment;
	}


	private function doRefreshState(Transaction $transaction, string $externalIdentifier): Payment
	{
		$payment = $this->getByExternalIdentifier($externalIdentifier);
		$externalState = $this->paymentDriver->refreshState($payment);

		if ($externalState->equals($payment->state->externalState)) {
			$this->logger->info('GoPayPaymentFacade: refreshState() did not result in change of external state', [
				'paymentId' => $payment->id,
				'externalIdentifier' => $payment->externalIdentifier,
				'externalState' => [
					'code' => $externalState->getCode()->getValue(),
					'data' => $externalState->getData(),
				],
			]);

		} else {
			$this->paymentService->advanceByExternalState(
				$transaction,
				$payment,
				$externalState
			);
		}

		return $payment;
	}
}
