<?php declare(strict_types = 1);

namespace MangoShop\Channel\Api;

use MangoShop\Channel\Model\Channel;
use MangoShop\Channel\Model\ChannelsRepository;
use MangoShop\Core\Api\EntityNotFoundException;
use MangoShop\Core\NextrasOrm\TransactionManager;
use Nextras\Orm\Collection\ICollection;


class ChannelFacade
{
	/** @var ChannelsRepository */
	private $channelsRepository;

	/** @var TransactionManager */
	private $transactionManager;


	public function __construct(ChannelsRepository $channelsRepository, TransactionManager $transactionManager)
	{
		$this->channelsRepository = $channelsRepository;
		$this->transactionManager = $transactionManager;
	}


	/**
	 * @return ICollection|Channel[]
	 */
	public function findAll(): ICollection
	{
		return $this->channelsRepository->findAll();
	}


	public function getById(int $channelId): Channel
	{
		$channel = $this->channelsRepository->getById($channelId);

		if ($channel === null) {
			throw new EntityNotFoundException(Channel::class, $channelId);
		}

		return $channel;
	}


	public function getByCode(string $channelCode): Channel
	{
		$channel = $this->channelsRepository->getBy(['code' => $channelCode]);

		if ($channel === null) {
			throw new EntityNotFoundException(Channel::class);
		}

		return $channel;
	}


	public function create(string $code, ChannelStruct $channelStruct): Channel
	{
		$transaction = $this->transactionManager->begin();

		$channel = new Channel(
			$code,
			$channelStruct->name,
			$channelStruct->defaultLocale,
			$channelStruct->pricingGroup,
			$channelStruct->checkoutOptionGroup
		);

		$channel->setLocales($channelStruct->locales, $channelStruct->defaultLocale);

		$transaction->persist($channel);

		$this->transactionManager->flush($transaction);

		return $channel;
	}


	public function update(Channel $channel, ChannelStruct $channelStruct): void
	{
		$transaction = $this->transactionManager->begin();

		$channel->setName($channelStruct->name);
		$channel->setLocales($channelStruct->locales, $channelStruct->defaultLocale);
		$channel->setPricingGroup($channelStruct->pricingGroup);
		$channel->setCheckoutOptionGroup($channelStruct->checkoutOptionGroup);

		$transaction->persist($channel);

		$this->transactionManager->flush($transaction);
	}
}
