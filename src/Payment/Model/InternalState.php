<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


class InternalState
{
	/** @var InternalStateCodeEnum */
	private $code;

	/** @var null|FailureReasonEnum */
	private $failureReason;


	public function __construct(InternalStateCodeEnum $code, ?FailureReasonEnum $failureReason)
	{
		$this->code = $code;
		$this->failureReason = $failureReason;
	}


	public function getCode(): InternalStateCodeEnum
	{
		return $this->code;
	}


	public function getFailureReason(): ?FailureReasonEnum
	{
		return $this->failureReason;
	}


	public function equals(self $other): bool
	{
		return $this->code === $other->code
			&& $this->failureReason === $other->failureReason;
	}
}
