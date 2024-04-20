<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;


class ExternalState
{
	/** @var IExternalStateCodeEnum */
	private $code;

	/** @var array */
	private $data;


	public function __construct(IExternalStateCodeEnum $code, array $data)
	{
		$this->code = $code;
		$this->data = $data;
	}


	public function getCode(): IExternalStateCodeEnum
	{
		return $this->code;
	}


	public function getData(): array
	{
		return $this->data;
	}


	public function equals(?self $other): bool
	{
		return $other !== null
			&& $this->code === $other->code
			&& $this->data === $other->data;
	}
}
