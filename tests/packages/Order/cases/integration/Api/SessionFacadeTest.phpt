<?php declare(strict_types = 1);

namespace MangoShopTests\Order\Cases\Integration\Api;

use MangoShop;
use MangoShop\Order\Api\SessionFacade;
use MangoShop\Order\Model\Session;
use MangoShop\Order\Model\SessionsRepository;
use Mangoweb\Tester\Infrastructure\TestCase;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../../.././../bootstrap.php';


/**
 * @testCase
 */
class SessionFacadeTest extends TestCase
{
	public function testCreate(SessionFacade $sessionFacade, SessionsRepository $sessionsRepository)
	{
		$token = $sessionFacade->createSessionToken();
		Assert::true(strlen($token) === 40);
		$session = $sessionsRepository->getBy(['token' => $token]);
		Assert::type(Session::class, $session);
		assert($session !== null);
		Assert::same($token, $session->token);
	}
}


SessionFacadeTest::run($containerFactory);
