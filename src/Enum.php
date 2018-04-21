<?php
declare(strict_types = 1);

namespace AL\PhpEnum;

abstract class Enum
{
	/** @var array */
	private static $values = [];

	/** @var array */
	private static $instances = [];
	

	/** @var string */
	protected $value;

	/** @var int */
	protected $order;


	protected function __construct(int $order, string $value)
	{
		$this->order = $order;
		$this->value = $value;
	}

	public function __toString()
	{
		return $this->value;
	}

	public function __sleep()
	{
		throw new EnumException('Enum cannot be serialize, because there is no way how to deserialize it.');
	}

	public function __wakeup()
	{
		throw new EnumException('Enum has to be unique. Only one instance has to exist. So there is no way how to deserialize it.');
	}

	public function __clone()
	{
		throw new EnumException('Enum has to be unique. Only one instance has to exist. So it cannot be cloned.');
	}


	public function getValue(): string
	{
		return $this->value;
	}

	public function getOrder(): int
	{
		return $this->order;
	}


	public static function __callStatic($name, $arguments)
	{
		if (!empty($arguments)) {
			throw new EnumException('Enum cannot be called with arguments.');
		}

		return static::parse($name);
	}


	public static function exists(string $value): bool
	{
		$values = static::getEnumValues(static::class);

		return isset($values[$value]);
	}


	public static function tryParse(string $value): ?Enum
	{
		return static::exists($value) ? static::getInstance(static::class, $value) : null;
	}
	
	public static function parse(string $value): Enum
	{
		$instance = static::tryParse($value);
		if ($instance === null) {
			$class = static::class;
			throw new EnumException("Unknown enum value $class::$value. Try to add anotation: @method static \$this $value()");
		}
		else {
			return $instance;
		}
	}


	public static function tryInOrder(int $order): ?Enum
	{
		$class = static::class;
		$values = array_keys(static::getEnumValues($class));

		return count($values) > $order && $order >= 0 ? static::getInstance($class, $values[$order]) : null;
	}

	public static function inOrder(int $order): Enum
	{
		$instance = static::tryInOrder($order);
		if ($instance === null) {
			$class = static::class;
			throw new EnumException("$class order out of range: $order");
		}
		else {
			return $instance;
		}
	}


	/**
	 * @return string[]
	 */
	protected static function findOutEnumValues(string $class): array
	{
		$reflection = new \ReflectionClass($class);
		$doc = $reflection->getDocComment();
		$classParts = explode('\\', $class);
		$className = array_pop($classParts);

		$matches = [];
		preg_match_all("~@method +static +(?:$className|\\\$this) +(\S+)\(\)~", $doc, $matches);

		return array_flip($matches[1]);
	}

	/**
	 * @return string[]
	 */
	protected static function getEnumValues(string $class): array
	{
		if (!isset(self::$values[$class])) {
			self::$values[$class] = static::findOutEnumValues($class);
		}

		return self::$values[$class];
	}

	protected static function getInstance(string $class, string $value): ?Enum
	{
		if (!isset(self::$instances[$class][$value])) {
			$values = static::getEnumValues($class);
			self::$instances[$class][$value] = new $class($values[$value], $value);
		}

		return self::$instances[$class][$value];
	}
}
