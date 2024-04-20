<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\IOrderProcessingTransition;
use MangoShop\Order\Model\Order;
use MangoShop\Order\Model\OrderProductItem;
use Nette\Utils\Validators;


class OrderWaitingToPrintTransition implements IOrderProcessingTransition
{
	/** @var string[] */
	private $pdfUrls;


	public function __construct(array $pdfUrls)
	{
		assert(Validators::everyIs($pdfUrls, 'url'));
		$this->pdfUrls = $pdfUrls;
	}


	public function createProcessing(Order $order): \MangoShop\Order\Model\OrderProcessing
	{
		assert($order->processing instanceof BicistickersOrderProcessing);
		assert($order->processing->isAllowed(BicistickersOrderProcessingStateEnum::WAITING_TO_PRINT()));

		$items = $order->productItems->fetchPairs('id');
		foreach ($this->pdfUrls as $id => $url) {
			$item = $items[$id];
			assert($item instanceof OrderProductItem);
			//todo set url to configuration
		}
		return new BicistickersOrderProcessing($order, BicistickersOrderProcessingStateEnum::WAITING_TO_PRINT(), ['pdfs' => $this->pdfUrls]);
	}
}
