<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
class AbstractEnumTest extends \PHPUnit\Framework\TestCase //{{{
{
    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage argument:OK
     */
    public function testInvalidArgumentException() //{{{
    {
        $enumType = new EnumType(EnumType::ENUM_TYPE_OK());
    } //}}}

    public function testConstruct() //{{{
    {
        $enumType = new EnumType(EnumType::ENUM_TYPE_OK);
    } //}}}

    public function testValueOf() //{{{
    {
        $enumType = new EnumType(EnumType::ENUM_TYPE_OK);
        $this->assertSame(EnumType::ENUM_TYPE_OK, $enumType->valueOf(), BardicheException::getMessageJson('Internal Server Error.assertEquals'));
    } //}}}

    public function testToString() //{{{
    {
        $enumType = new EnumType(EnumType::ENUM_TYPE_OK);
        $this->assertSame(EnumType::ENUM_TYPE_OK, $enumType->__toString(), BardicheException::getMessageJson('Internal Server Error.assertEquals'));
    } //}}}
} //}}}

final class EnumType extends AbstractEnum //{{{
{
    const ENUM_TYPE_OK = 'OK';
    const ENUM_TYPE_NG = 'NG';
} //}}}
