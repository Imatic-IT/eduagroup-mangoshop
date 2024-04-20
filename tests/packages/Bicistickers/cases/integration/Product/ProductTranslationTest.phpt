<?php declare(strict_types = 1);

namespace MangoShopTests\Bicistickers\Cases\Integration\Api;

use MangoShop\Locale\Model\Locale;
use MangoShop\Product\Model\Product;
use MangoShopTests\Bicistickers\Inc\BicistickersHook;
use MangoShopTests\EntityGenerator;
use Mangoweb\Tester\Infrastructure\Container\IAppContainerHook;
use Mangoweb\Tester\Infrastructure\TestCase;
use Nette\Configurator;
use Nette\DI\Container;
use Nextras\Dbal\Connection;
use Tester\Assert;


$configurator = require __DIR__ . '/../../../../../bootstrap-configurator.php';


/**
 * @testCase
 */
class ProductTranslationTest extends TestCase
{
	/** @var EntityGenerator */
	private $entityGenerator;


	public function __construct(EntityGenerator $entityGenerator)
	{
		$this->entityGenerator = $entityGenerator;
	}


	public function testFetchAllTranslations(Connection $connection)
	{
		$connection->query('SET sql_mode = "STRICT_TRANS_TABLES,STRICT_ALL_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION";');
		$connection->query('INSERT INTO [wp_posts] %values', [
			'ID' => 1001,
			'post_title' => 'EN product name',
			'post_content' => 'EN product content',
			'post_excerpt' => '',
			'to_ping' => '',
			'pinged' => '',
			'post_content_filtered' => '',
			'post_type' => 'product',
		]);

		$connection->query('INSERT INTO [wp_posts] %values', [
			'ID' => 1002,
			'post_title' => 'CS product name',
			'post_content' => 'CS product content',
			'post_excerpt' => '',
			'to_ping' => '',
			'pinged' => '',
			'post_content_filtered' => '',
			'post_type' => 'product',
		]);

		$connection->query('INSERT INTO [wp_icl_translations] %values', [
			'translation_id' => 2001,
			'element_type' => 'post_product',
			'element_id' => 1001,
			'trid' => 777,
			'language_code' => 'en',
			'source_language_code' => NULL,
		]);

		$connection->query('INSERT INTO [wp_icl_translations] %values', [
			'translation_id' => 2002,
			'element_type' => 'post_product',
			'element_id' => 1002,
			'trid' => 777,
			'language_code' => 'cs',
			'source_language_code' => 'en',
		]);

		$enLocale = $this->entityGenerator->create(Locale::class, ['code' => 'en']);
		$csLocale = $this->entityGenerator->create(Locale::class, ['code' => 'cs']);
		$deLocale = $this->entityGenerator->create(Locale::class, ['code' => 'de']);

		$productA = $this->entityGenerator->create(Product::class, ['code' => 'product-1001']);
		$productB = $this->entityGenerator->create(Product::class, ['code' => 'product-1002']);

		$rows = $connection->query('SELECT * FROM product_translations ORDER BY id')->fetchAll();
		Assert::count(2, $rows);
		Assert::same(['id' => 2001, 'product_id' => 1, 'locale_id' => 1, 'name' => 'EN product name'], $rows[0]->toArray());
		Assert::same(['id' => 2002, 'product_id' => 1, 'locale_id' => 2, 'name' => 'CS product name'], $rows[1]->toArray());

		Assert::count(2, $productA->translations->fetchAll());
		Assert::same('EN product name', $productA->getTranslation($enLocale)->name);
		Assert::same('CS product name', $productA->getTranslation($csLocale)->name);
		Assert::null($productA->getTranslation($deLocale));

		Assert::count(0, $productB->translations->fetchAll());
		Assert::null($productB->getTranslation($enLocale));
		Assert::null($productB->getTranslation($csLocale));
		Assert::null($productB->getTranslation($deLocale));
	}


	protected static function getContainerHook(Container $testContainer): ?IAppContainerHook
	{
		return new BicistickersHook();
	}
}


assert($configurator instanceof Configurator);

$configurator->addConfig(__DIR__ . '/../../../inc/bicistickers.infrastructure-config.neon');
ProductTranslationTest::run([$configurator, 'createContainer']);
