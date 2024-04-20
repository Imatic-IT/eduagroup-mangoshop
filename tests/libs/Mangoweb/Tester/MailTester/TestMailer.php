<?php declare(strict_types = 1);

namespace Mangoweb\MailTester;

use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Mail\SendException;
use Nette\SmartObject;


class TestMailer implements IMailer
{
	use SmartObject;

	/** @var Message[] */
	public $messages = [];

	/** @var SendException[] */
	public $exceptions = [];


	public function send(Message $mail)
	{
		$exception = array_shift($this->exceptions);
		if ($exception) {
			throw $exception;
		}
		$this->messages[] = $mail;
	}


	public function clear()
	{
		$this->messages = [];
	}
}
