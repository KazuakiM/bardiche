<?php

namespace KazuakiM\Bardiche;

class FileClientsTest extends \PHPUnit_Framework_TestCase
{
    // Class variable {{{
    protected $successftpConfig;
    //}}}

    protected function setUp() : void //{{{
    {
        $this->successftpConfig = [
            'negotiation' => true,
            'timeout'     => 90,
            'host'        => '127.0.0.1',
            'username'    => 'fate',
            'password'    => 'rynith',
            'file_info'   => [
                [
                    'remote_directory_path' => '/',
                    'remote_file_name'      => 'sample_remote.txt',
                    'local_directory_path'  => '',
                    'local_file_name'       => 'sample_local.txt',
                ],
            ],
            'port'  => 10021,
            'pasv'  => true,
            'ascii' => true,
            'ssl'   => false,
        ];
    } //}}}

    public function testOne() //{{{
    {
        foreach ($this->successftpConfig['file_info'] as $fileInfoArray) {
            $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
            $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

            FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successftpConfig, FileClients::BARDICHE_UPLOAD);
            $this->assertFileExists($remoteFilePath, BardicheException::getMessageJson('Not found.assertFileExists'));
            $this->assertFileEquals($remoteFilePath, $localFilePath, BardicheException::getMessageJson('Not found.assertFileEquals'));
        }
    } //}}}
}
