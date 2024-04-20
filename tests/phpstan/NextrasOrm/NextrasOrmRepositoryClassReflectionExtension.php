<?php declare(strict_types = 1);

namespace MangoShopTests\PhpStan\NextrasOrm;

use Nette\Utils\Strings;
use Nextras\Orm\Repository\IRepository;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\Php\PhpMethodReflectionFactory;


class NextrasOrmRepositoryClassReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareClassReflectionExtension
{

	/** @var PhpMethodReflectionFactory */
	private $methodReflectionFactory;

	/** @var Broker */
	private $broker;


	public function __construct(PhpMethodReflectionFactory $methodReflectionFactory)
	{
		$this->methodReflectionFactory = $methodReflectionFactory;
	}


	public function setBroker(Broker $broker)
	{
		$this->broker = $broker;
	}


	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		if (!$classReflection->getNativeReflection()->implementsInterface(IRepository::class)) {
			return FALSE;
		}
		if ($classReflection->getName() === 'Nextras\Orm\Repository\Repository') {
			return FALSE;
		}

		$doc = $classReflection->getNativeReflection()->getDocComment();

		$virtuals = Strings::matchAll($doc, '~
			@method
			\s+
			(?P<types>\S+)
			\s+
			(?P<name>\w+)
		~mx');

		$mapperClass = preg_replace('~Repository$~', 'Mapper', $classReflection->getName());
		$mapperReflection = $this->broker->getClass($mapperClass);

		foreach ($virtuals as $virtual) {
			if ($virtual['name'] === $methodName) {
				return $mapperReflection->hasMethod($methodName);
			}
		}
		return FALSE;
	}


	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		// Intentionally not asking PhpStan reflection, because we changed
		// the behaviour of hasMethod.
		if ($classReflection->getNativeReflection()->hasMethod($methodName)) {
			$ref = new \ReflectionMethod($classReflection->getName(), $methodName);
			return $this->methodReflectionFactory->create($classReflection, $ref, []);
		}

		// magic method
		$mapperClass = preg_replace('~Repository$~', 'Mapper', $classReflection->getName());
		$mapperReflection = $this->broker->getClass($mapperClass);
		return $mapperReflection->getMethod($methodName, new Scope($this->broker));
	}

}
