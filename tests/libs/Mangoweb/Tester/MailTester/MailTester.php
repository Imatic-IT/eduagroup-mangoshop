<?php declare(strict_types = 1);

namespace Mangoweb\MailTester;

use Nette\Mail\Message;
use Tester\Assert;


class MailTester
{
	/** @var TestMailer */
	private $mailer;


	public function __construct(TestMailer $mailer)
	{
		$this->mailer = $mailer;
	}


	public function assertNone(): void
	{
		Assert::count(0, $this->mailer->messages, 'Unconsumed e-mails sent');
	}


	/**
	 * @return TestMessage[]
	 */
	public function consumeAll(): array
	{
		$messages = $this->mailer->messages;
		$this->mailer->clear();
		return array_map(function (Message $message) {
			return new TestMessage($message);
		}, $messages);
	}


	public function consumeSingle(): TestMessage
	{
		Assert::true(count($this->mailer->messages) > 0);
		return new TestMessage(array_shift($this->mailer->messages));
	}
}
