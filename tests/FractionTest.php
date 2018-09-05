<?php
declare(strict_types=1);
namespace Lamansky\Fraction\Test;

require_once __DIR__ . "/../src/Fraction.php";

use Lamansky\Fraction\Fraction;
use PHPUnit\Framework\TestCase;

final class FractionTest extends TestCase {

    public function testMethods() : void {
        $f = new Fraction(1, 2);
        $this->assertEquals(false, $f->isNegative());
        $this->assertEquals(0, $f->getMixedInteger());
        $this->assertEquals(1, $f->getMixedNumerator());
        $this->assertEquals(1, $f->getNumerator());
        $this->assertEquals(2, $f->getDenominator());
        $this->assertEquals([1, 2], $f->getParts());
        $this->assertEquals([0, 1, 2], $f->getMixedParts());
        $this->assertEquals(0.5, $f->toFloat());
        $this->assertNotEquals(1.5, $f->toFloat());
    }

    public function testFractionFromFloats() : void {
        $f = new Fraction(1, 1.5);
        $this->assertEquals(2, $f->getNumerator());
        $this->assertEquals(3, $f->getDenominator());

        $f = new Fraction(13, 0.00013);
        $this->assertEquals(100000, $f->getNumerator());
        $this->assertEquals(1, $f->getDenominator());
    }

    public function test_fromFloat() : void {
        $f = Fraction::fromFloat(0.5);
        $this->assertEquals(1, $f->getNumerator());
        $this->assertEquals(2, $f->getDenominator());
    }

    public function testFractionSimplification() : void {
        $f = new Fraction(4, 6);
        $this->assertEquals(2, $f->getNumerator());
        $this->assertEquals(3, $f->getDenominator());
    }

    public function testLargerNumerator() : void {
        $f = new Fraction(40, 20);
        $this->assertEquals(2, $f->getNumerator());
        $this->assertEquals(1, $f->getDenominator());
        $this->assertEquals(2, $f->toFloat());
    }

    public function testZeroNumerator() : void {
        $f = new Fraction(0, 5);
        $this->assertEquals(0, $f->getNumerator());
        $this->assertEquals(1, $f->getDenominator());
        $this->assertEquals(0, $f->toFloat());
    }

    public function testZeroDenominator() : void {
        $this->expectException(\RangeException::class);
        $f = new Fraction(10, 0);
    }

    public function testMixedFractions() : void {
        $f = new Fraction(3, 2);
        $this->assertEquals(3, $f->getNumerator());
        $this->assertEquals(2, $f->getDenominator());
        $this->assertEquals([3, 2], $f->getParts());

        $this->assertEquals(1, $f->getMixedInteger());
        $this->assertEquals(1, $f->getMixedNumerator());
        $this->assertEquals([1, 1, 2], $f->getMixedParts());
    }

    public function testNegativeFractions() : void {
        $fs = [
            new Fraction(2, -4),
            new Fraction(2, 4, true),
            new Fraction(2, 4, -1),
        ];
        foreach ($fs as $f) {
            $this->assertEquals(true, $f->isNegative());
            $this->assertEquals(1, $f->getNumerator());
            $this->assertEquals(2, $f->getDenominator());
            $this->assertEquals(-0.5, $f->toFloat());
        }
    }

    public function test_clone() : void {
        $f1 = new Fraction(1, 2);
        $this->assertEquals(false, $f1->isNegative());
        $this->assertEquals(1, $f1->getNumerator());
        $this->assertEquals(2, $f1->getDenominator());
        $f2 = $f1->clone();
        $this->assertNotSame($f1, $f2);
        $this->assertEquals(false, $f2->isNegative());
        $this->assertEquals(1, $f2->getNumerator());
        $this->assertEquals(2, $f2->getDenominator());
    }

    public function test_absolute() : void {
        $f1 = new Fraction(1, 2, true);
        $this->assertEquals(true, $f1->isNegative());
        $this->assertEquals(1, $f1->getNumerator());
        $this->assertEquals(2, $f1->getDenominator());
        $f2 = $f1->absolute();
        $this->assertNotSame($f1, $f2);
        $this->assertEquals(false, $f2->isNegative());
        $this->assertEquals(1, $f2->getNumerator());
        $this->assertEquals(2, $f2->getDenominator());
    }

    public function test_toString() : void {
        $this->assertEquals('1/2', (new Fraction(1, 2))->toString());

        $this->assertEquals('1/3', (new Fraction(1, 3))->toString());
        $this->assertEquals('2/3', (new Fraction(2, 3))->toString());

        $this->assertEquals('1/4', (new Fraction(1, 4))->toString());
        $this->assertEquals('3/4', (new Fraction(3, 4))->toString());

        $this->assertEquals('1/5', (new Fraction(1, 5))->toString());
        $this->assertEquals('2/5', (new Fraction(2, 5))->toString());
        $this->assertEquals('3/5', (new Fraction(3, 5))->toString());
        $this->assertEquals('4/5', (new Fraction(4, 5))->toString());

        $this->assertEquals('1/6', (new Fraction(1, 6))->toString());
        $this->assertEquals('5/6', (new Fraction(5, 6))->toString());

        $this->assertEquals('1/7', (new Fraction(1, 7))->toString());

        $this->assertEquals('1/8', (new Fraction(1, 8))->toString());
        $this->assertEquals('3/8', (new Fraction(3, 8))->toString());
        $this->assertEquals('5/8', (new Fraction(5, 8))->toString());
        $this->assertEquals('7/8', (new Fraction(7, 8))->toString());

        $this->assertEquals('1/9', (new Fraction(1, 9))->toString());
        $this->assertEquals('1/10', (new Fraction(1, 10))->toString());

        $this->assertEquals('0', (new Fraction(0, 1))->toString());
        $this->assertEquals('1', (new Fraction(1, 1))->toString());
        $this->assertEquals('2', (new Fraction(2, 1))->toString());
        $this->assertEquals('3', (new Fraction(3, 1))->toString());
        $this->assertEquals('4', (new Fraction(4, 1))->toString());
        $this->assertEquals('5', (new Fraction(5, 1))->toString());
        $this->assertEquals('6', (new Fraction(6, 1))->toString());
        $this->assertEquals('7', (new Fraction(7, 1))->toString());
        $this->assertEquals('8', (new Fraction(8, 1))->toString());
        $this->assertEquals('9', (new Fraction(9, 1))->toString());

        $this->assertEquals('1 1/2', (new Fraction(3, 2))->toString());
        $this->assertEquals('-1 1/2', (new Fraction(-3, 2))->toString());

        $this->assertEquals('1234567890/12345678901', (new Fraction(1234567890, 12345678901))->toString());
        $this->assertEquals((new Fraction(-2, 43))->toString(), '-2/43');

    }

    public function test_toUnicodeString() : void {
        $this->assertEquals('½', (new Fraction(1, 2))->toUnicodeString());

        $this->assertEquals('⅓', (new Fraction(1, 3))->toUnicodeString());
        $this->assertEquals('⅔', (new Fraction(2, 3))->toUnicodeString());

        $this->assertEquals('¼', (new Fraction(1, 4))->toUnicodeString());
        $this->assertEquals('¾', (new Fraction(3, 4))->toUnicodeString());

        $this->assertEquals('⅕', (new Fraction(1, 5))->toUnicodeString());
        $this->assertEquals('⅖', (new Fraction(2, 5))->toUnicodeString());
        $this->assertEquals('⅗', (new Fraction(3, 5))->toUnicodeString());
        $this->assertEquals('⅘', (new Fraction(4, 5))->toUnicodeString());

        $this->assertEquals('⅙', (new Fraction(1, 6))->toUnicodeString());
        $this->assertEquals('⅚', (new Fraction(5, 6))->toUnicodeString());

        $this->assertEquals('⅐', (new Fraction(1, 7))->toUnicodeString());

        $this->assertEquals('⅛', (new Fraction(1, 8))->toUnicodeString());
        $this->assertEquals('⅜', (new Fraction(3, 8))->toUnicodeString());
        $this->assertEquals('⅝', (new Fraction(5, 8))->toUnicodeString());
        $this->assertEquals('⅞', (new Fraction(7, 8))->toUnicodeString());

        $this->assertEquals('⅑', (new Fraction(1, 9))->toUnicodeString());
        $this->assertEquals('⅒', (new Fraction(1, 10))->toUnicodeString());

        $this->assertEquals('0', (new Fraction(0, 1))->toUnicodeString());
        $this->assertEquals('1', (new Fraction(1, 1))->toUnicodeString());
        $this->assertEquals('2', (new Fraction(2, 1))->toUnicodeString());
        $this->assertEquals('3', (new Fraction(3, 1))->toUnicodeString());
        $this->assertEquals('4', (new Fraction(4, 1))->toUnicodeString());
        $this->assertEquals('5', (new Fraction(5, 1))->toUnicodeString());
        $this->assertEquals('6', (new Fraction(6, 1))->toUnicodeString());
        $this->assertEquals('7', (new Fraction(7, 1))->toUnicodeString());
        $this->assertEquals('8', (new Fraction(8, 1))->toUnicodeString());
        $this->assertEquals('9', (new Fraction(9, 1))->toUnicodeString());

        $this->assertEquals('1½', (new Fraction(3, 2))->toUnicodeString());

        $this->assertEquals('¹²³⁴⁵⁶⁷⁸⁹⁰⁄₁₂₃₄₅₆₇₈₉₀₁', (new Fraction(1234567890, 12345678901))->toUnicodeString());
        $this->assertEquals((new Fraction(-2, 43))->toUnicodeString(), '−²⁄₄₃');

    }

    public function test_add() : void {
        $f = (new Fraction(2, 3))->add(new Fraction(1, 4));
        $this->assertEquals($f->getNumerator(), 11);
        $this->assertEquals($f->getDenominator(), 12);
    }

    public function test_subtract() : void {
        $f = (new Fraction(2, 3))->subtract(new Fraction(2, 8));
        $this->assertEquals($f->getNumerator(), 5);
        $this->assertEquals($f->getDenominator(), 12);
    }

    public function test_multiply() : void {
        $f = (new Fraction(1, 2))->multiply(new Fraction(4, 16));
        $this->assertEquals($f->getNumerator(), 1);
        $this->assertEquals($f->getDenominator(), 8);
    }

    public function test_divide() : void {
        $f = (new Fraction(1, 2))->divide(new Fraction(1, 4));
        $this->assertEquals($f->getNumerator(), 2);
        $this->assertEquals($f->getDenominator(), 1);
    }
}
