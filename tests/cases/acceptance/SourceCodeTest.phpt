<?php declare(strict_types = 1);

namespace MangoShopTests\Acceptance\Model;

use MangoShopTests\Analysis\SourceCode;
use MangoShopTests\Analysis\SourceCodeProvider;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nette\Neon\Exception;
use Nette\Neon\Neon;
use Nette\Utils\Strings;
use Tester\Assert;

$containerFactory = require __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class SourceCodeTest extends TestCase
{

	/** @var SourceCodeProvider */
	private $codeProvider;


	public function __construct(SourceCodeProvider $codeProvider)
	{
		$this->codeProvider = $codeProvider;
	}


	public function testAllFiles()
	{
		foreach ($this->codeProvider->getFiles() as $file) {
			assert($file instanceof SourceCode);

			if ($file->is(SourceCode::SOURCE)) {
				$this->assertStrictModeOn($file);
			}
			if ($file->is(SourceCode::ORM_ENTITY)) {
				$this->assertEntityPropertiesReadOnly($file);
			}
			if ($file->is(SourceCode::TEST_CASE)) {
				$this->assertCorrectRequire($file);
				$this->assertTestFileExtension($file);
			}
			if ($file->is(SourceCode::NEON)) {
				$this->assertValidNeonFile($file);
			}
			if ($file->is(SourceCode::INI)) {
				$this->assertValidIniFile($file);
			}
		}
		Assert::true(TRUE, 'All tests passed correctly');
	}


	private function assertStrictModeOn(SourceCode $file)
	{
		$source = $file->getSource();

		if (!preg_match('~^<\?php declare\(strict_types(=| = )1\);~', $source)) {
			Assert::fail("strict_types not properly declared in '$file'");
		}
	}


	private function assertCorrectRequire(SourceCode $file)
	{
		if (preg_match('~\$container\s*=\s*require_once~', $file->getSource())) {
			Assert::fail("Invalid usage of require_once in '$file', use require instead.");
		}
	}


	private function assertTestFileExtension(SourceCode $file)
	{
		if ($file->getFileInfo()->getExtension() === 'php') {
			Assert::fail("Invalid extension of test case '$file', use '.phpt' instead.");
		}
	}


	private function assertValidNeonFile(SourceCode $file)
	{
		try {
			Neon::decode($file->getSource());
		} catch (Exception $exception) {
			Assert::fail("Configuration neon file '$file' is not valid: " . $exception->getMessage());
		}
	}


	private function assertValidIniFile(SourceCode $file)
	{
		$parsed = parse_ini_file($file->getFileInfo()->getPathname(), TRUE, INI_SCANNER_NORMAL);
		if ($parsed === FALSE) {
			Assert::fail("Configuration ini file '$file' could not be parsed.");
		}
	}


	private function assertEntityPropertiesReadOnly(SourceCode $file)
	{
		$fails = Strings::matchAll($file->getSource(), '~@property(?!-read)(\s+(?P<context>.*?\$\S+))?~i');
		if ($fails) {
			echo "\e[31mBroken encapsulation:\e[0m\n";
			echo "    $file\n";
			foreach ($fails as $error) {
				$context = Strings::replace($error['context'], '~\s\s+~', ' ');
				echo "        @property $context \n";
			}
			echo "\e[1;33mChange those properties to @property-read and use setters\e[0m\n";
			Assert::fail(count($fails) . " encapsulation violations");
		}

		Assert::true(TRUE, 'Entity has only read-only properties');
	}

}


SourceCodeTest::run($containerFactory);
