<?php
namespace Lamansky\Fraction;

class Fraction {
    private $negative;
    private $a;
    private $b;

    public function __construct ($a, $b, $negative = null) {
        if ($b === 0) {
            throw new \RangeException('Denominator cannot be zero');
        }

        if (is_null($negative)) {
            $this->negative = ($a < 0) !== ($b < 0);
        } elseif (is_int($negative) || is_float($negative)) {
            $this->negative = $negative < 0;
        } else {
            $this->negative = !!$negative;
        }

        $a = abs($a);
        $b = abs($b);
        $multiple = static::getFloatToIntegerMultiple($a, $b);
        $a = round($a * $multiple);
        $b = round($b * $multiple);
        [$a, $b] = static::simplify($a, $b);

        $this->a = $a;
        $this->b = $b;
    }

    public static function fromFloat (float $f) : self {
        return new static($f, 1);
    }

    public function isNegative () : bool {
        return $this->negative;
    }

    public function getSignMultiplier () : int {
        return $this->isNegative() ? -1 : 1;
    }

    public function getNumerator () : int {
        return $this->a;
    }

    public function getMixedInteger () : int {
        return $this->a > $this->b ? floor($this->a / $this->b) : 0;
    }

    public function getMixedNumerator () : int {
        return $this->a > $this->b ? $this->a % $this->b : $this->a;
    }

    public function getDenominator () : int {
        return $this->b;
    }

    public function getParts () : array {
        return [$this->a, $this->b];
    }

    public function getMixedParts () : array {
        return [$this->getMixedInteger(), $this->getMixedNumerator(), $this->getDenominator()];
    }

    public function toString () : string {
        $prefix = $this->isNegative() ? '-' : '';
        if ($this->b === 1) { return $prefix . $this->a; }

        $i = $this->getMixedInteger();
        if ($i > 0) { $prefix .= $i . ' '; }

        return $prefix . $this->getMixedNumerator() . '/' . $this->getDenominator();
    }

    public function toUnicodeString () : string {
        $prefix = $this->isNegative() ? "\u{2212}" : '';
        if ($this->b === 1) { return $prefix . $this->a; }

        $i = $this->getMixedInteger();
        if ($i > 0) { $prefix .= $i; }

        $single_char = static::getSingleUnicodeCharacter($this->getMixedNumerator(), $this->getDenominator());
        if (!is_null($single_char)) { return $prefix . $single_char; }

        $super = ["\u{2070}", "\u{b9}", "\u{b2}", "\u{b3}", "\u{2074}", "\u{2075}", "\u{2076}", "\u{2077}", "\u{2078}", "\u{2079}"];
        $sub = ["\u{2080}", "\u{2081}", "\u{2082}", "\u{2083}", "\u{2084}", "\u{2085}", "\u{2086}", "\u{2087}", "\u{2088}", "\u{2089}"];

        $numerator = implode(array_map(function ($n) use ($super) {
            return $super[$n];
        }, static::getDigits($this->getMixedNumerator())));

        $denominator = implode(array_map(function ($n) use ($sub) {
            return $sub[$n];
        }, static::getDigits($this->getDenominator())));

        return $prefix . $numerator . "\u{2044}" . $denominator;
    }

    protected static function getDigits (int $n) : array {
        if ($n === 0) { return [0]; }
        $n = abs($n);
        $digits = [];
        while ($n > 0) {
            array_unshift($digits, $n % 10);
            $n = floor($n / 10);
        }
        return $digits;
    }

    protected static function getSingleUnicodeCharacter (int $a, int $b) : ?string {
        switch ($b) {
            case 2:
                switch ($a) {
                    case 1:
                        return "\u{bd}";
                }
                break;
            case 3:
                switch ($a) {
                    case 1:
                        return "\u{2153}";
                    case 2:
                        return "\u{2154}";
                }
                break;
            case 4:
                switch ($a) {
                    case 1:
                        return "\u{bc}";
                    case 3:
                        return "\u{be}";
                }
                break;
            case 5:
                switch ($a) {
                    case 1:
                        return "\u{2155}";
                    case 2:
                        return "\u{2156}";
                    case 3:
                        return "\u{2157}";
                    case 4:
                        return "\u{2158}";
                }
                break;
            case 6:
                switch ($a) {
                    case 1:
                        return "\u{2159}";
                    case 5:
                        return "\u{215a}";
                }
                break;
            case 7:
                switch ($a) {
                    case 1:
                        return "\u{2150}";
                }
                break;
            case 8:
                switch ($a) {
                    case 1:
                        return "\u{215b}";
                    case 3:
                        return "\u{215c}";
                    case 5:
                        return "\u{215d}";
                    case 7:
                        return "\u{215e}";
                }
                break;
            case 9:
                switch ($a) {
                    case 1:
                        return "\u{2151}";
                }
                break;
            case 10:
                switch ($a) {
                    case 1:
                        return "\u{2152}";
                }
                break;
        }
        return null;
    }

    public function toFloat () : float {
        return floatval($this->a / $this->b) * $this->getSignMultiplier();
    }

    public function clone () : self {
        return new static($this->a, $this->b, $this->negative);
    }

    public function absolute () : self {
        return new static($this->a, $this->b, false);
    }

    public function add (self $other) : self {
        $a1 = $this->getNumerator() * $this->getSignMultiplier();
        $b1 = $this->getDenominator();
        $a2 = $other->getNumerator() * $other->getSignMultiplier();
        $b2 = $other->getDenominator();
        return new static(($a1 * $b2) + ($a2 * $b1), $b1 * $b2);
    }

    public function subtract (self $other) : self {
        $a1 = $this->getNumerator() * $this->getSignMultiplier();
        $b1 = $this->getDenominator();
        $a2 = $other->getNumerator() * $other->getSignMultiplier();
        $b2 = $other->getDenominator();
        return new static(($a1 * $b2) - ($a2 * $b1), $b1 * $b2);
    }

    public function multiply (self $other) : self {
        return new static($this->getNumerator() * $other->getNumerator(), $this->getDenominator() * $other->getDenominator(), $this->isNegative() !== $other->isNegative());
    }

    public function divide (self $other) : self {
        return new static($this->getNumerator() * $other->getDenominator(), $this->getDenominator() * $other->getNumerator(), $this->isNegative() !== $other->isNegative());
    }

    protected static function getFloatToIntegerMultiple ($a, $b) : int {
        $a_decimals = static::countDecimals($a);
        $b_decimals = static::countDecimals($b);
        return pow(10, max($a_decimals, $b_decimals));
    }

    protected static function countDecimals ($num) : int {
        if (is_float($num)) {
            for ($d = 0; $num != round($num, $d); $d++); // phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall
            return $d;
        } elseif (is_int($num)) {
            return 0;
        }
        throw new \InvalidArgumentException();
    }

    protected static function simplify (int $a, int $b) : array {
        $gcd = static::getGreatestCommonDivisor($a, $b);
        return [$a / $gcd, $b / $gcd];
    }

    protected static function getGreatestCommonDivisor (int $a, int $b) : int {
        if ($a < $b) { [$b, $a] = [$a, $b]; }
        if ($b === 0) { return $a; }
        $r = $a % $b;
        while ($r > 0) {
            $a = $b;
            $b = $r;
            $r = $a % $b;
        }
        return $b;
    }
}
