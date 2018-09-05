# Fraction

A PHP class that represents a fraction. Converts to/from floats (0.25 ↔ ¼), simplifies fractions (⁴⁄₈ → ½), handles mathematical operations (½ + ⅓), supports negative fractions (−⅘), and does Unicode string output. Requires PHP 7.1 or above.

## Installation

Use [Composer](http://getcomposer.org):

```bash
composer require lamansky/fraction
```

## API

The library consists of a single class: `Lamansky\Fraction\Fraction`.

### Constructor Parameters

1. `$a` (int or float): The numerator of the fraction (the number on top).
2. `$b` (int or float): The denominator of the fraction (the number on bottom).
3. Optional: `$negative` (bool or int): If set to `true` or `-1` (or any negative number), the fraction will be negative. If set to `false` or `1` (or any positive number), the fraction will be positive. If omitted, the fraction will be negative only if `$a` or `$b` is negative (but not both). If provided, the value of `$negative` will override whatever sign values `$a` or `$b` may have.

### Static Method: `fromFloat() : Fraction`

Accepts one parameter (a `float` number) and returns a `Fraction`. The `Fraction` will have the same sign value (positive/negative) as the float.

### `isNegative() : bool`

No parameters. Returns `true` if the fraction is negative; otherwise `false`.

### `getSignMultiplier() : int`

No parameters. Returns `-1` if the fraction is negative, or `1` if it is positive.

### `getNumerator() : int`

No parameters. Returns the numerator of the fraction (the number on top).

### `getMixedInteger() : int`

No parameters. Returns the integer component of a mixed fraction. A mixed fraction is one which is simplified to use a whole number (e.g. ⁵⁄₄ → 1¼). Example:

```php
$f = new Fraction(7, 2);
echo $f->toString(); // 3 1/2
echo $f->getMixedInteger(); // 3
echo $f->getMixedNumerator(); // 1
echo $f->getDenominator(); // 2
```

If the fraction is not mixed (i.e. if the numerator is smaller than the denominator), this function will return `0`.

### `getMixedNumerator() : int`

No parameters. Returns the numerator of a mixed fraction. A mixed fraction is one which is simplified to use a whole number (e.g. ⁵⁄₄ → 1¼). Example:

```php
$f = new Fraction(5, 4);
echo $f->getNumerator(); // 5
echo $f->getMixedNumerator(); // 1
```

If the fraction is not mixed (i.e. if the numerator is smaller than the denominator), this function will return the normal numerator.

### `getDenominator() : int`

No parameters. Returns the denominator of the fraction (the number on bottom).

### `getParts() : array`

No parameters. Returns an array with two elements: the numerator and the denominator.

### `getMixedParts() : array`

No parameters. Returns an array with three elements: the mixed-fraction integer, the mixed-fraction numerator, and the denominator. For example: for the fraction 2¼, it would return `[2, 1, 4]`.

### `toString() : string`

No parameters. Returns an ASCII string representation of the fraction.

```php
$f = new Fraction(-5, 4);
echo $f->toString(); // '-1 1/4'
```

### `toUnicodeString() : string`

No parameters. Returns a Unicode string representation of the fraction.

```php
$f = new Fraction(-5, 4);
echo $f->toUnicodeString(); // '−1¼'
```

### `toFloat() : float`

No parameters. Divides the numerator by the denominator and returns a floating-point number.

```php
$f = new Fraction(-5, 4);
echo $f->toFloat(); // -1.25
```

### `clone() : Fraction`

No parameters. Returns a `Fraction` with the same numerator, denominator, and positive/negative sign.

### `absolute() : Fraction`

No parameters. Clones the `Fraction`, but makes it positive if it’s negative.

### `add(Fraction $other) : Fraction`

Returns a `Fraction` that is the sum of the current fraction and `$other`.

Note that if `$other` is a negative fraction, this will end up being subtraction (just like in math).

### `subtract(Fraction $other) : Fraction`

Subtracts `$other` from the current fraction and returns the result.

### `multiply(Fraction $other) : Fraction`

Multiplies the current fraction by `$other` and returns the result.

### `divide(Fraction $other) : Fraction`

Divides the current fraction by `$other` and returns the result.
