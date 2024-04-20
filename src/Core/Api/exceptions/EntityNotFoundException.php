<?php declare(strict_types = 1);

namespace MangoShop\Core\Api;

use Mangoweb\ExceptionResponsibility\ResponsibilityClient;


class EntityNotFoundException extends \RuntimeException implements ResponsibilityClient
{
	/** @var string */
	private $entityClassName;

	/** @var null|int */
	private $entityId;


	public function __construct(string $entityClassName, ?int $entityId = null)
	{
		$this->entityClassName = $entityClassName;
		$this->entityId = $entityId;

		$message = $this->entityId === null
			? "Required entity $entityClassName was not found"
			: "Required entity $entityClassName with id $entityId was not found";

		parent::__construct($message);
	}


	public function getEntityClassName(): string
	{
		return $this->entityClassName;
	}


	public function getEntityId(): ?int
	{
		return $this->entityId;
	}
}
