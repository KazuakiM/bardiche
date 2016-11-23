<?php

use KazuakiM\Bardiche\BardicheException;
use KazuakiM\Bardiche\FileClientsType;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
class EnumTest extends \PHPUnit_Framework_TestCase //{{{
{
    /**
     * @expectedException Error
     */
    public function testConstruct() //{{{
    {
        $fileClientsType = new FileClientsType(FileClientsType::BARDICHE_TYPE_FTP);
    } //}}}

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentException() //{{{
    {
        require __DIR__ . '/../../src/FileClients.php';

        $fileClientsType = new FileClientsType(FileClientsType::BARDICHE_TYPE_FTP());
    } //}}}

    public function testToString() //{{{
    {
        $fileClientsType = new FileClientsType(FileClientsType::BARDICHE_TYPE_FTP);
        $this->assertEquals(FileClientsType::BARDICHE_TYPE_FTP, $fileClientsType, BardicheException::getMessageJson('Internal Server Error.assertEquals'));
    } //}}}
} //}}}
