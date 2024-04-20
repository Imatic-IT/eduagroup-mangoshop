<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use Mangoweb\ExceptionResponsibility\ResponsibilityThirdParty;
use Throwable;


class GoPayRequestFailedException extends PaymentException implements ResponsibilityThirdParty
{
	/** @var \GoPay\Http\Response */
	private $response;


	public function __construct(Payment $payment, \GoPay\Http\Response $response, ?Throwable $previous = null)
	{
		parent::__construct(
			$payment,
			sprintf('GoPay returned response with HTTP code %d', $response->statusCode),
			$previous
		);

		$this->response = $response;
	}


	public function getResponse(): \GoPay\Http\Response
	{
		return $this->response;
	}
}
