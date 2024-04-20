<?php declare(strict_types = 1);

namespace MangoShop\Order\Model;

use DateTimeImmutable;
use MabeEnum\Enum;
use MangoShop\Core\NextrasOrm\Entity;
use Mangoweb\Clock\Clock;


/**
 * @property-read null|OrderProcessing $previousVersion {m:1 OrderProcessing, oneSided=true}
 * @property-read DateTimeImmutable    $createdAt
 * @property-read Enum                 $state           {container \MangoShop\Core\NextrasOrm\EnumProperty}
 * @property-read array                $data            {container \MangoShop\Core\NextrasOrm\JsonProperty}
 */
abstract class OrderProcessing extends Entity
{
	public function __construct(Order $order, Enum $state, array $data = [])
	{
		parent::__construct();
		$this->setReadOnlyValue('createdAt', Clock::now());
		$this->setReadOnlyValue('state', $state);
		$this->setReadOnlyValue('data', $data);
		$this->setReadOnlyValue('previousVersion', $order->processing);
	}


	abstract public function isDispatched(): bool;
}
