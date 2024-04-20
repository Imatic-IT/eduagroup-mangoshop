<?php declare(strict_types = 1);

namespace MangoShopTests\Acceptance\Model;

use Mangoweb\ExceptionResponsibility\ResponsibilityApp;
use Mangoweb\ExceptionResponsibility\ResponsibilityClient;
use Mangoweb\ExceptionResponsibility\ResponsibilityEndUser;
use Mangoweb\ExceptionResponsibility\ResponsibilityNobody;
use Mangoweb\ExceptionResponsibility\ResponsibilityThirdParty;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nette\Loaders\RobotLoader;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class ExceptionResponsibilityTest extends TestCase
{

	/** @var RobotLoader */
	private $robotLoader;


	public function __construct(RobotLoader $robotLoader)
	{
		$this->robotLoader = $robotLoader;
	}


	public function testAllExceptions()
	{
		foreach ($this->robotLoader->getIndexedClasses() as $className => $file) {
			$rc = new \ReflectionClass($className);
			if (is_subclass_of($className, \Exception::class, TRUE) && strpos($className, 'Mangoweb\\') !== 0 && !$rc->isAbstract()) {
				$this->assertResponsibilityDefined($className);
			}
		}
	}


	private function assertResponsibilityDefined(string $className)
	{
		$implements = 0;
		foreach ([
			ResponsibilityApp::class,
			ResponsibilityClient::class,
			ResponsibilityEndUser::class,
			ResponsibilityNobody::class,
			ResponsibilityThirdParty::class,
		] as $responsibility) {
			$implements += is_subclass_of($className, $responsibility, TRUE);
		}

		if ($implements !== 1) {
			Assert::fail("Exception '$className' implements $implements responsibility interfaces, 1 expected");
		}
		Assert::$counter += 1;
	}

}


ExceptionResponsibilityTest::run($containerFactory);
