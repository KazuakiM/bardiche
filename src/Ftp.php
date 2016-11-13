<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
trait Ftp
{
    // Class variable {{{
    private $_connection;
    //}}}

    public function initFtp() : void //{{{
    {
        //Connection
        if ($this->type === FileClientsType::BARDICHE_TYPE_FTPS) {
            $this->_connection = ftp_ssl_connect($this->config['host'], $this->config['port'], $this->config['timeout']);
        } else {
            $this->_connection = ftp_connect($this->config['host'], $this->config['port'], $this->config['timeout']);
        }
        if ($this->_connection === false) {
            throw new BardicheException(BardicheException::getMessageJson('ftp_connect error.'));
        }

        //Login
        if (!@ftp_login($this->_connection, $this->config['username'], $this->config['password'])) {
            throw new BardicheException(BardicheException::getMessageJson('ftp_login error.'));
        }

        //Pasv
        if (!ftp_pasv($this->_connection, $this->config['pasv'])) {
            throw new BardicheException(BardicheException::getMessageJson('ftp_pasv error.'));
        }
    } //}}}

    public function uploadFtp() : void //{{{
    {
        foreach ($this->config['file_info'] as $fileInfoArray) {
            if ($this->config['ascii']) {
                $result = @ftp_put($this->_connection, ltrim(self::getRemoteFilePath($fileInfoArray), '/'), self::getUploadLocalFilePath($fileInfoArray), FTP_ASCII);
            } else {
                $result = @ftp_put($this->_connection, ltrim(self::getRemoteFilePath($fileInfoArray), '/'), self::getUploadLocalFilePath($fileInfoArray), FTP_BINARY);
            }
            if (!$result) {
                throw new BardicheException(BardicheException::getMessageJson('ftp_put error.'));
            }
        }
    } //}}}

    public function downloadFtp() : void //{{{
    {
        foreach ($this->config['file_info'] as $fileInfoArray) {
            if ($this->config['ascii']) {
                $result = @ftp_get($this->_connection, self::getDownloadLocalFilePath($fileInfoArray), ltrim(self::getRemoteFilePath($fileInfoArray), '/'), FTP_ASCII);
            } else {
                $result = @ftp_get($this->_connection, self::getDownloadLocalFilePath($fileInfoArray), ltrim(self::getRemoteFilePath($fileInfoArray), '/'), FTP_BINARY);
            }
            if (!$result) {
                throw new BardicheException(BardicheException::getMessageJson('ftp_get error.'));
            }
        }
    } //}}}

    public function closeFtp() : void //{{{
    {
        @ftp_close($this->_connection);
    } //}}}
}
