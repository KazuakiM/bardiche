<?php

namespace KazuakiM\Bardiche;

class FileClientsTest extends \PHPUnit_Framework_TestCase
{
    // Class variable {{{
    protected $successftpConfig;
    private static $_remoteRootDirectory = '/tmp/ftp';
    //}}}

    protected function setUp() : void //{{{
    {
        $this->successftpConfig = [
            'negotiation' => true,                             // options default: fallse
            'timeout'     => 90,                               // options default: 90
            'host'        => '127.0.0.1',
            'username'    => 'fate',
            'password'    => 'pass',
            'file_info'   => [
                [
                    'remote_directory_path' => '/',
                    'remote_file_name'      => 'sample_remote.txt',
                    'local_directory_path'  => '/tmp',
                    'local_file_name'       => 'sample_local.txt',
                ],
            ],
            'port'  => 10021,                                  // options default: 21
            'pasv'  => false,                                  // options default: true
            'ascii' => true,                                   // options default: true
        ];
    } //}}}

    public function testOne() //{{{
    {
        $this->assertTrue(touch('/tmp/sample_local.txt'), BardicheException::getMessageJson('Internal Server Error.touch'));
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successftpConfig, FileClients::BARDICHE_UPLOAD);

        foreach ($this->successftpConfig['file_info'] as $fileInfoArray) {
            $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
            $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

            $this->assertFileExists(self::$_remoteRootDirectory . $remoteFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileExists'));
            $this->assertFileEquals(self::$_remoteRootDirectory . $remoteFilePath, $localFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileEquals'));
        }
    } //}}}
}
