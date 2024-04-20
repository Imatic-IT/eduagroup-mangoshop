<?php declare(strict_types = 1);

namespace Mangoweb\MailTester;

use Nette\Mail\Message;
use Tester\Assert;


class TestMessage
{
	/** @var Message */
	private $message;


	public function __construct(Message $message)
	{
		$this->message = $message;
	}


	public function assertSubject(string $subject): self
	{
		Assert::same($subject, $this->message->subject);
		return $this;
	}


	public function assertRecipient(string $email): self
	{
		$recipients = array_keys($this->message->getHeader('To'));
		Assert::count(1, $recipients);
		Assert::same($email, reset($recipients));
		return $this;
	}


	/**
	 * @param string|array $match
	 */
	public function assertBody($match): self
	{
		if (is_array($match)) {
			$match = '%A?%' . implode('%A?%', $match) . '%A?%';
		}
		assert(is_string($match));
		Assert::match($match, $this->message->getBody());

		return $this;
	}

}
