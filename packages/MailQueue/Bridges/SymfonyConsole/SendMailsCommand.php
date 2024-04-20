<?php declare(strict_types = 1);

namespace Mangoweb\MailQueue\Bridges\SymfonyConsole;

use Mangoweb\Clock\Clock;
use Mangoweb\MailQueue\MailSender;
use Mangoweb\MailQueue\MailSenderException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SendMailsCommand extends Command
{
	/** @var MailSender */
	private $mailSender;

	/** @var LoggerInterface */
	private $logger;


	public function __construct(MailSender $mailSender, LoggerInterface $logger)
	{
		parent::__construct('mail:send-queued');
		$this->mailSender = $mailSender;
		$this->logger = $logger;
	}


	protected function configure()
	{
		parent::configure();
		$this->addOption('limit', 'l', InputOption::VALUE_REQUIRED, '', 0);
		$this->addOption('worker', 'w', InputOption::VALUE_NONE);
		$this->addOption('sleep', 's', InputOption::VALUE_REQUIRED, '', 1);
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$count = 0;
		$limit = (int) $input->getOption('limit');
		$worker = (bool) $input->getOption('worker');
		$sleep = (int) $input->getOption('sleep');

		if (function_exists('pcntl_signal')) {
			// SIGINT handler (which is invoked by CTRL+C) is replaced by custom handler
			// now SIGINT signal is not dispatched until pcntl_signal_dispatch() is called
			// then it exits normally
			pcntl_signal(SIGINT, function () {
				exit;
			});
		}

		while ($limit === 0 || $count < $limit) {
			Clock::refresh();
			if (function_exists('pcntl_signal_dispatch')) {
				pcntl_signal_dispatch();
			}

			try {
				$id = $this->mailSender->sendOne();
				if ($id !== null) {
					$this->logger->debug('Queued mail was successfully sent.', ['messageId' => $id]);
					$count++;
				} elseif ($worker) {
					sleep($sleep);
				} else {
					break;
				}

			} catch (MailSenderException $e) {
				$this->logger->error('Queued mail sending has failed.', ['exception' => $e, 'messageId' => $e->getMessageId()]);
			}
		}
	}
}
