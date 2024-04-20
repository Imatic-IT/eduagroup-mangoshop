<?php declare(strict_types = 1);

namespace MangoShop\Payment\Model;

use MangoShop\Core\NextrasOrm\Repository;
use Nextras\Orm\Collection\ICollection;


/**
 * @method PaymentMethodsMapper getMapper()
 * @method PaymentMethod hydrateEntity(array $data)
 * @method PaymentMethod attach(PaymentMethod $entity)
 * @method void detach(PaymentMethod $entity)
 * @method PaymentMethod|NULL getBy(array $conds)
 * @method PaymentMethod|NULL getById(int $primaryValue)
 * @method ICollection|PaymentMethod[] findAll()
 * @method ICollection|PaymentMethod[] findBy(array $where)
 * @method ICollection|PaymentMethod[] findById(int [] $primaryValues)
 * @method PaymentMethod|NULL persist(PaymentMethod $entity, bool $withCascade = true)
 * @method PaymentMethod|NULL persistAndFlush(PaymentMethod $entity, bool $withCascade = true)
 * @method PaymentMethod remove(int|PaymentMethod $entity, bool $withCascade = true)
 * @method PaymentMethod removeAndFlush(int|PaymentMethod $entity, bool $withCascade = true)
 */
class PaymentMethodsRepository extends Repository
{
	/** @var array code => class */
	private static $paymentMethods = [];


	public static function registerPaymentMethod(string $code, string $class): void
	{
		assert(is_a($class, PaymentMethod::class, true));
		assert(!isset(self::$paymentMethods[$code]) || self::$paymentMethods[$code] === $class, "Payment method $code is already registered as $class");

		self::$paymentMethods[$code] = $class;
	}


	public static function getEntityClassNames(): array
	{
		return array_merge([PaymentMethod::class], self::getDefinedEntityClassNames(self::class));
	}


	public function getEntityClassName(array $data): string
	{
		$code = $data['code'];
		assert(isset(self::$paymentMethods[$code]), "Payment method $code is not registered");

		return self::$paymentMethods[$code];
	}
}
