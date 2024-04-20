<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

use MangoShop\Order\Model\Order;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

class OrderMailer
{
	/** @var string */
	private $fromEmail;

	/** @var string */
	private $fromName;

	/** @var IMailer */
	private $mailer;

	/** @var IOrderMailContentFactory */
	private $mailContentFactory;


	public function __construct(string $fromEmail, string $fromName, IMailer $mailer, IOrderMailContentFactory $mailContentFactory)
	{
		$this->mailer = $mailer;
		$this->mailContentFactory = $mailContentFactory;
		$this->fromEmail = $fromEmail;
		$this->fromName = $fromName;
	}


	public function sendOrderSummary(Order $order): void
	{
		$content = $this->mailContentFactory->createSummaryContent($order);
		//todo invoice
		$this->sendMessage($order, $content);
	}


	public function sendOrderDispatchInfo(Order $order): void
	{
		$content = $this->mailContentFactory->createDispatchInfoContent($order);
		$this->sendMessage($order, $content);
	}


	public function sendPaymentRequest(Order $order): void
	{
		//todo not called
		$content = $this->mailContentFactory->createPaymentRequestContent($order);
		$this->sendMessage($order, $content);
	}


	private function sendMessage(Order $order, OrderMailContent $content): void
	{
		$message = new Message();
		$message->addTo((string) $order->customer->email, $order->shippingInfo->address->recipientName);
		$message->setFrom($this->fromEmail, $this->fromName);
		$message->setSubject($content->getSubject());
		$message->setHtmlBody($content->getHtmlBody());
		$this->mailer->send($message);
	}
}
