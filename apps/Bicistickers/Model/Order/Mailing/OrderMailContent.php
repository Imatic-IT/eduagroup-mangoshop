<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Model;

class OrderMailContent
{
	/** @var string */
	private $subject;

	/** @var string */
	private $htmlBody;


	public function __construct(string $subject, string $htmlBody)
	{
		$this->subject = $subject;
		$this->htmlBody = $htmlBody;
	}


	public function getSubject(): string
	{
		return $this->subject;
	}


	public function getHtmlBody(): string
	{
		return $this->htmlBody;
	}
}
