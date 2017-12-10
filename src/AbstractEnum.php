<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @see      https://github.com/KazuakiM/bardiche
 */
abstract class AbstractEnum
{
    private $_scalar;

    public function __construct($value) //{{{
    {
        $ref = new \ReflectionObject($this);
        $consts = $ref->getConstants();
        if (!in_array($value, $consts, true)) {
            throw new \InvalidArgumentException(sprintf('argument:%s', $value));
        }

        $this->_scalar = $value;
    } //}}}

    /**
     * @return object Class Object
     */
    final public static function __callStatic(string $label, array $args) //{{{
    {
        $class = get_called_class();
        $const = constant(sprintf('%s::%s', $class, $label));

        return new $class($const);
    } //}}}

    public function __toString() //{{{
    {
        return (string) $this->_scalar;
    } //}}}

    /**
     * @return mixed Value
     */
    final public function valueOf() //{{{
    {
        return $this->_scalar;
    } //}}}
}
