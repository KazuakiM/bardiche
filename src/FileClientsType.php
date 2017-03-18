<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
final class FileClientsType extends AbstractEnum //{{{
{
    const BARDICHE_TYPE_FTP = 'ftp';
    const BARDICHE_TYPE_FTPS = 'ftps';
    const BARDICHE_TYPE_SFTP = 'sftp';
    const BARDICHE_TYPE_SCP = 'scp';
} //}}}
