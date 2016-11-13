<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
trait Ssh
{
    // Class variable {{{
    private $_connection, $_sftp;
    //}}}

    public function initSsh() : void //{{{
    {
        //Connection
        $this->_connection = @ssh2_connect($this->config['host'], $this->config['port'], $this->config['method'], $this->config['callbacks']);
        if ($this->_connection === false) {
            throw new BardicheException(BardicheException::getMessageJson('ssh2_connect error.'));
        }

        //Login
        if (0 < strlen($this->config['pubkeyfile']) || 0 < strlen($this->config['privkeyfile'])) {
            $result = ssh2_auth_pubkey_file($this->_connection, $this->config['username'], $this->config['pubkeyfile'], $this->config['privkeyfile'], $this->config['password']);
        } else {
            $result = ssh2_auth_password($this->_connection, $this->config['username'], $this->config['password']);
        }
        if (!$result) {
            throw new BardicheException(BardicheException::getMessageJson('ssh2_auth_ error.'));
        }

        //Sftp
        if ($this->type === FileClientsType::BARDICHE_TYPE_SFTP) {
            $this->_sftp = ssh2_sftp($this->_connection);
        }
    } //}}}

    public function closeSsh() : void //{{{
    {
        @ssh2_exec($this->_connection, 'exit;');
    } //}}}

    public function uploadSftp() : void //{{{
    {
        foreach ($this->config['file_info'] as $fileInfoArray) {
            if (@file_put_contents("ssh2.sftp://{$this->_sftp}" . self::getRemoteFilePath($fileInfoArray), @fopen(self::getUploadLocalFilePath($fileInfoArray), 'r')) === false) {
                throw new BardicheException(BardicheException::getMessageJson('file_put_contents or fopen error.'));
            }

        }
    } //}}}

    public function downloadSftp() : void //{{{
    {
        foreach ($this->config['file_info'] as $fileInfoArray) {
            if (@file_put_contents(self::getDownloadLocalFilePath($fileInfoArray), @fopen("ssh2.sftp://{$this->_sftp}" . self::getRemoteFilePath($fileInfoArray), 'r'), LOCK_EX) === false) {
                throw new BardicheException(BardicheException::getMessageJson('file_put_contents or fopen error.'));
            }
        }
    } //}}}

    public function uploadScp() : void //{{{
    {
        foreach ($this->config['file_info'] as $fileInfoArray) {
            if (@ssh2_scp_send($this->_connection, self::getUploadLocalFilePath($fileInfoArray), self::getRemoteFilePath($fileInfoArray), $this->config['permission']) === false) {
                throw new BardicheException(BardicheException::getMessageJson('ssh2_scp_send error.'));
            }
        }
    } //}}}

    public function downloadScp() : void //{{{
    {
        foreach ($this->config['file_info'] as $fileInfoArray) {
            if (@ssh2_scp_recv($this->_connection, self::getRemoteFilePath($fileInfoArray), self::getDownloadLocalFilePath($fileInfoArray)) === false) {
                throw new BardicheException(BardicheException::getMessageJson('ssh2_scp_recv error.'));
            }
        }
    } //}}}
}
