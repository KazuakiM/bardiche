<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
abstract class Enum
{
    private $_scalar;

    public function __construct($value) //{{{
    {
        $ref    = new \ReflectionObject($this);
        $consts = $ref->getConstants();
        if (!in_array($value, $consts, true)) {
            throw new InvalidArgumentException();
        }

        $this->_scalar = $value;
    } //}}}

    final public static function __callStatic(string $label, array $args) //{{{
    {
        $class = get_called_class();
        $const = constant("$class::$label");

        return new $class($const);
    } //}}}

    final public function valueOf() //{{{
    {
        return $this->_scalar;
    } //}}}

    final public function __toString() : string //{{{
    {
        return (string) $this->_scalar;
    } //}}}
}
