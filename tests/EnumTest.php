<?php
declare(strict_types = 1);

namespace AL\PhpEnum\Tests;

use AL\PhpEnum\Enum;
use PHPUnit\Framework\TestCase;

/**
 * @method static $this VALUE1() description
 * @method static TestedEnum VALUE_2()
 * @method $this WITHOUT_STATIC()
 * @method static $this WITHOUT_BRACES
 * @method static $this __xxx___()
 */
final class TestedEnum extends Enum {}

/**
 * @method static $this VALUE1() description
 */
final class OtherEnum extends Enum {}


class EnumTest extends TestCase
{	
	public function testGetOrder(): void
	{
		static::assertSame(0, TestedEnum::VALUE1()->getOrder());
		static::assertSame(1, TestedEnum::VALUE_2()->getOrder());
		static::assertSame(2, TestedEnum::__xxx___()->getOrder());
	}

	public function testGetValue(): void
	{
		static::assertSame('VALUE1', TestedEnum::VALUE1()->getValue());
		static::assertSame('VALUE_2', TestedEnum::VALUE_2()->getValue());
		static::assertSame('__xxx___', TestedEnum::__xxx___()->getValue());
	}

	public function testIdentity(): void
	{
		static::assertTrue(TestedEnum::VALUE_2() === TestedEnum::VALUE_2());
	}

	public function testEquality(): void
	{
		static::assertTrue(TestedEnum::VALUE_2() == TestedEnum::VALUE_2());
	}


	public function testCastToString(): void
	{
		static::assertSame('VALUE1', (string)TestedEnum::VALUE1());
		static::assertSame('VALUE_2', (string)TestedEnum::VALUE_2());
		static::assertSame('__xxx___', (string)TestedEnum::__xxx___());
	}

	public function testParse(): void
	{
		$enum = TestedEnum::parse('VALUE1');
		static::assertSame('VALUE1', $enum->getValue());
		static::assertSame(TestedEnum::VALUE1(), $enum);
	}

	/**
	 * @expectedException \AL\PhpEnum\EnumException
	 */
	public function testParseUnknown(): void
	{
		TestedEnum::parse('NOT_EXIST');
	}

	public function testTryParse(): void
	{
		$enum = TestedEnum::tryParse('VALUE1');
		static::assertSame('VALUE1', $enum->getValue());
		static::assertSame((string)TestedEnum::VALUE1(), (string)$enum);
	}

	public function testTryParseUnknown(): void
	{
		$enum = TestedEnum::tryParse('NOT_EXIST');
		static::assertNull($enum);
	}


	public function testInOrder(): void
	{
		static::assertSame((string)TestedEnum::VALUE1(), (string)TestedEnum::inOrder(0));
		static::assertSame((string)TestedEnum::__xxx___(), (string)TestedEnum::inOrder(2));
	}

	/**
	 * @expectedException \AL\PhpEnum\EnumException
	 */
	public function testInOrderOverflow(): void
	{
		TestedEnum::inOrder(3);
	}

	/**
	 * @expectedException \AL\PhpEnum\EnumException
	 */
	public function testInOrderUnderflow(): void
	{
		TestedEnum::inOrder(-1);
	}

	public function testTryInOrder(): void
	{
		static::assertSame((string)TestedEnum::VALUE1(), (string)TestedEnum::tryInOrder(0));
		static::assertSame((string)TestedEnum::__xxx___(), (string)TestedEnum::tryInOrder(2));
	}

	public function testTryInOrderOverflow(): void
	{
		$enum = TestedEnum::tryInOrder(3);
		static::assertNull($enum);
	}

	public function testTryInOrderUnderflow(): void
	{
		$enum = TestedEnum::tryInOrder(-1);
		static::assertNull($enum);
	}


	/**
	 * @expectedException \AL\PhpEnum\EnumException
	 */
	public function testNotExist(): void
	{
		TestedEnum::NOT_EXIST();
	}

	/**
	 * @expectedException \AL\PhpEnum\EnumException
	 */
	public function testCloneDisabled(): void
	{
		clone TestedEnum::VALUE1();
	}

	/**
	 * @expectedException \AL\PhpEnum\EnumException
	 */
	public function testSleepDisabled(): void
	{
		serialize(TestedEnum::VALUE1());
	}


	public function testDifferentEnums(): void
	{
		static::assertFalse(TestedEnum::VALUE1() === OtherEnum::VALUE1());
		static::assertFalse(TestedEnum::VALUE1() == OtherEnum::VALUE1());
	}
}
