<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


class PaymentInitializationResponse
{
	/** @var Payment */
	private $payment;

	/** @var string */
	private $externalIdentifier;

	/** @var ExternalState */
	private $externalState;

	/** @var null|string */
	private $gatewayUrl;


	public function __construct(Payment $payment, string $externalIdentifier, ExternalState $externalState, ?string $gatewayUrl)
	{
		$this->payment = $payment;
		$this->externalIdentifier = $externalIdentifier;
		$this->externalState = $externalState;
		$this->gatewayUrl = $gatewayUrl;
	}


	public function getPayment(): Payment
	{
		return $this->payment;
	}


	public function getExternalIdentifier(): string
	{
		return $this->externalIdentifier;
	}


	public function getExternalState(): ExternalState
	{
		return $this->externalState;
	}


	public function getGatewayUrl(): ?string
	{
		return $this->gatewayUrl;
	}
}
