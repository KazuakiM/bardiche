<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
class FileClientsTest extends \PHPUnit_Framework_TestCase //{{{
{
    // Class variable {{{
    protected $successUploadFtpConfig;
    protected $successUploadScpConfig;
    private static $_remoteFtpRootDirectory = '/tmp/ftp';
    //}}}

    protected function setUp() //{{{
    {
        $this->successUploadFtpConfig = [ //{{{
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

        $this->successUploadScpConfig = [ //{{{
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
            'port'        => 21,                               // options default: 21
            'method'      => [],                               // options
            'callbacks'   => [],                               // options
            'pubkeyfile'  => '',                               // options
            'privkeyfile' => '',                               // options
            'permission'  => 0644,                             // options default: 0644
        ]; //}}}
    } //}}}

    public static function assertFilePath(array $fileInfoArray, string $directoryPath, string $fileName) : bool //{{{
    {
        assert(array_key_exists($directoryPath, $fileInfoArray), BardicheException::getMessageJson(sprintf("Not found.fileInfoArray['%s']", $directoryPath)));
        assert(array_key_exists($fileName,      $fileInfoArray), BardicheException::getMessageJson(sprintf("Not found.fileInfoArray['%s']", $fileName)));

        return true;
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"fsockopen error"}
     */
    public function testNegotiation() //{{{
    {
        $this->successUploadFtpConfig['port'] = '2222';
        $fileClients                          = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig);
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"type:fate error"}
     */
    public function testUploadFile() //{{{
    {
        $fileClients       = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig);
        $fileClients->type = 'fate';
        $fileClients->uploadFile();
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"type:fate error"}
     */
    public function testDownloadFile() //{{{
    {
        $fileClients       = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig);
        $fileClients->type = 'fate';
        $fileClients->DownloadFile();
    } //}}}

    public function testSetOptions() //{{{
    {
        $fileClients   = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig);
        $fileInfoArray = [
            'file_info' => [
                [
                    'remote_directory_path' => '/',
                    'remote_file_name'      => 'override_wait.txt',
                    'local_directory_path'  => '/tmp',
                    'local_file_name'       => 'override_wait.txt',
                    'ascii'                 => FTP_ASCII,
                ],
            ],
        ];
        $fileClients->setOptions($fileInfoArray);
        $this->successUploadFtpConfig['file_info'] = $fileInfoArray['file_info'];
        $this->assertArraySubset($fileClients->getConfig(), $this->successUploadFtpConfig);
    } //}}}

    public function testSetValue() //{{{
    {
        $fileClients = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig);
        $timout      = 300;
        $fileClients->setValue('timeout', $timout);
        $this->successUploadFtpConfig['timeout'] = $timout;
        $this->assertArraySubset($fileClients->getConfig(), $this->successUploadFtpConfig);
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"file_exists error."}
     */
    public function testGetUploadLocalFilePath() //{{{
    {
        $this->assertTrue(@file_exists('/tmp/sample_ftp_local.txt') ? @unlink('/tmp/sample_ftp_local.txt') : true, BardicheException::getMessageJson('Internal Server Error.unlink'));

        $fileClients       = new FileClients(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig);
        $fileClientsConfig = $fileClients->getConfig();
        foreach ($fileClientsConfig['file_info'] as $fileInfoArray) {
            $fileClients->getUploadLocalFilePath($fileInfoArray);
        }
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"ftp_connect error."}
     */
    public function testAuthConnectionFtp() //{{{
    {
        $this->successUploadFtpConfig['negotiation'] = false;
        $this->successUploadFtpConfig['port']        = '2222';
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig, FileClients::BARDICHE_UPLOAD);
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"ftp_login error."}
     */
    public function testAuthLoginFtp() //{{{
    {
        $this->successUploadFtpConfig['username'] = 'ng_user';
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig, FileClients::BARDICHE_UPLOAD);
    } //}}}

    /**
     * @expectedException        KazuakiM\Bardiche\BardicheException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage {"message":"ftp_pasv error."}
     */
    public function testAuthPasvFtp() //{{{
    {
        $this->successUploadFtpConfig['pasv'] = [];
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig, FileClients::BARDICHE_UPLOAD);
    } //}}}

    public function testOneUploadFtp() //{{{
    {
        $this->assertTrue(@touch('/tmp/sample_ftp_local.txt'), BardicheException::getMessageJson('Internal Server Error.touch'));
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig, FileClients::BARDICHE_UPLOAD);

        foreach ($this->successUploadFtpConfig['file_info'] as $fileInfoArray) {
            $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
            $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

            $this->assertFileExists(self::$_remoteFtpRootDirectory . $remoteFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileExists'));
            $this->assertFileEquals(self::$_remoteFtpRootDirectory . $remoteFilePath, $localFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileEquals'));
        }
    } //}}}

    public function testOneDownloadFtp() //{{{
    {
        $this->successUploadFtpConfig['file_info'] = [
            [
                'remote_directory_path' => '/',
                'remote_file_name'      => 'sample_ftp_remote.txt',
                'local_directory_path'  => '/tmp',
                'local_file_name'       => 'sample_ftp_dl_local.txt',
                'ascii'                 => FTP_ASCII,
            ],
        ];
        FileClients::one(FileClientsType::BARDICHE_TYPE_FTP(), $this->successUploadFtpConfig, FileClients::BARDICHE_DOWNLOAD);

        foreach ($this->successUploadFtpConfig['file_info'] as $fileInfoArray) {
            $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
            $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

            $this->assertFileExists(self::$_remoteFtpRootDirectory . $remoteFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileExists'));
            $this->assertFileEquals(self::$_remoteFtpRootDirectory . $remoteFilePath, $localFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileEquals'));
        }
    } //}}}

    //TODO: extention 'ssh2' error.
    //public function testOneUploadScp() //{{{
    //{
    //    $this->assertTrue(touch('/tmp/sample_scp_local.txt'), BardicheException::getMessageJson('Internal Server Error.touch'));
    //    FileClients::one(FileClientsType::BARDICHE_TYPE_SCP(), $this->successUploadScpConfig, FileClients::BARDICHE_UPLOAD);

    //    foreach ($this->successUploadScpConfig['file_info'] as $fileInfoArray) {
    //        $localFilePath  = FileClients::getUploadLocalFilePath($fileInfoArray);
    //        $remoteFilePath = FileClients::getRemoteFilePath($fileInfoArray);

    //        $this->assertFileExists($remoteFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileExists'));
    //        $this->assertFileEquals($remoteFilePath, $localFilePath, BardicheException::getMessageJson('Internal Server Error.assertFileEquals'));
    //    }
    //} //}}}
} //}}}
