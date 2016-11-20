<?php

namespace KazuakiM\Bardiche;

class FileClientsTest extends \PHPUnit_Framework_TestCase
{
    // Class variable {{{
    protected $successftpConfig, $successScpConfig;
    private static $_remoteFtpRootDirectory = '/tmp/ftp';
    //}}}

    protected function setUp() //{{{
    {
        // ftp {{{
        $this->successftpConfig = [
            'negotiation' => true,                             // options default: fallse
            'timeout'     => 90,                               // options default: 90
            'host'        => '127.0.0.1',
            'username'    => 'fate',
            'password'    => 'pass',
            'file_info'   => [
                [
                    'remote_directory_path' => '/',
                    'remote_file_name'      => 'sample_ftp_remote.txt',
                    'local_directory_path'  => '/tmp',
                    'local_file_name'       => 'sample_ftp_local.txt',
                    'ascii'                 => FTP_ASCII,
                ],
            ],
            'port'     => 10021,                               // options default: 21
            'pasv'     => false,                               // options default: true
            'parallel' => 2,                                   // options default: 0
        ]; //}}}

        // scp {{{
        $this->successScpConfig = [
            'negotiation' => true,                             // options default: fallse
            'timeout'     => 90,                               // options default: 90
            'host'        => '127.0.0.1',
            'username'    => 'fate',
            'password'    => 'pass',
            'file_info'   => [
                [
                    'remote_directory_path' => '/tmp',
                    'remote_file_name'      => 'sample_scp_remote.txt',
                    'local_directory_path'  => '/tmp',
                    'local_file_name'       => 'sample_scp_local.txt',
                ],
            ],
            'port'        => 10021,                            // options default: 21
            'method'      => [],                               // options
            'callbacks'   => [],                               // options
            'pubkeyfile'  => '',                               // options
            'privkeyfile' => '',                               // options
            'permission'  => 0644,                             // options default: 0644
        ]; //}}}
    } //}}}

    public function testOneFtp() //{{{
    {
        $this->assertTrue(touch('/tmp/sample_ftp_local.txt'), BardicheException::getMessageJson('Internal Server Error.touch'));
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successftpConfig, FileClients::BARDICHE_UPLOAD);

        foreach ($this->successftpConfig['file_info'] as $fileInfoArray) {
            $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
            $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

            $this->assertFileExists(self::$_remoteFtpRootDirectory . $remoteFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileExists'));
            $this->assertFileEquals(self::$_remoteFtpRootDirectory . $remoteFilePath, $localFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileEquals'));
        }
    } //}}}

    //TODO: extention 'ssh2' error.
    //public function testOneScp() //{{{
    //{
    //    $this->assertTrue(touch('/tmp/sample_scp_local.txt'), BardicheException::getMessageJson('Internal Server Error.touch'));
    //    FileClients::one(FileClientsType::BARDICHE_TYPE_SCP(), $this->successScpConfig, FileClients::BARDICHE_UPLOAD);

    //    foreach ($this->successScpConfig['file_info'] as $fileInfoArray) {
    //        $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
    //        $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

    //        $this->assertFileExists($remoteFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileExists'));
    //        $this->assertFileEquals($remoteFilePath, $localFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileEquals'));
    //    }
    //} //}}}
}
