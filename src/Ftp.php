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
    private $_connectionArray = [];
    //}}}

    private function _auth() //{{{
    {
        $countFileInfo  = count($this->config['file_info']);
        $connectionSize = ($countFileInfo < $this->config['parallel']) ? $countFileInfo : $this->config['parallel'];
        for ($index = 0; $index <= $connectionSize; $index++) {
            //Connection
            if ($this->type === FileClientsType::BARDICHE_TYPE_FTPS) {
                $this->_connectionArray[$index] = [
                    'resource' => ftp_ssl_connect($this->config['host'], $this->config['port'], $this->config['timeout']),
                    'status'   => FTP_FINISHED,
                ];
            } else {
                $this->_connectionArray[$index] = [
                    'resource' => ftp_connect($this->config['host'], $this->config['port'], $this->config['timeout']),
                    'status'   => FTP_FINISHED,
                ];
            }
            if ($this->_connectionArray[$index]['resource'] === false) {
                throw new BardicheException(BardicheException::getMessageJson('ftp_connect error.'));
            }

            //Login
            if (!@ftp_login($this->_connectionArray[$index]['resource'], $this->config['username'], $this->config['password'])) {
                throw new BardicheException(BardicheException::getMessageJson('ftp_login error.'));
            }

            //Pasv
            if (!@ftp_pasv($this->_connectionArray[$index]['resource'], $this->config['pasv'])) {
                throw new BardicheException(BardicheException::getMessageJson('ftp_pasv error.'));
            }
        }
    } //}}}

    private function _wait() //{{{
    {
        while(true) {
            $endFlag = true;
            foreach ($this->_connectionArray as $key => $connection) {
                if ($connection['status'] ==  FTP_MOREDATA) {
                    $this->_connectionArray[$key]['status'] = @ftp_nb_continue($connection['resource']);
                    $endFlag                                = false;
                    break;
                }
            }
            if ($endFlag) {
                break;
            }
        }
    } //}}}

    public function uploadFtp() //{{{
    {
        $this->_auth();

        foreach ($this->config['file_info'] as $fileInfoArray) {
            assert(isset($fileInfoArray['ascii']), BardicheException::getMessageJson("Not found.config['ascii']"));

            while(true) {
                $setFlag = true;
                foreach ($this->_connectionArray as $key => $connection) {
                    if ($connection['status'] !=  FTP_MOREDATA) {
                        $this->_connectionArray[$key]['status'] = @ftp_nb_put($connection['resource'], ltrim(self::getRemoteFilePath($fileInfoArray), '/'), self::getUploadLocalFilePath($fileInfoArray), $fileInfoArray['ascii']);
                        $setFlag                                = true;
                        break;
                    } else {
                        $this->_connectionArray[$key]['status'] = @ftp_nb_continue($connection['resource']);
                    }
                }
                if ($setFlag) {
                    break;
                }
            }
        }

        $this->_wait();
    } //}}}

    public function downloadFtp() //{{{
    {
        $this->_auth();

        foreach ($this->config['file_info'] as $fileInfoArray) {
            assert(isset($fileInfoArray['ascii']), BardicheException::getMessageJson("Not found.config['ascii']"));

            while(true) {
                $setFlag = true;
                foreach ($this->_connectionArray as $key => $connection) {
                    if ($connection['status'] !=  FTP_MOREDATA) {
                        $this->_connectionArray[$key]['status'] = @ftp_nb_get($connection['resource'], self::getDownloadLocalFilePath($fileInfoArray), ltrim(self::getRemoteFilePath($fileInfoArray), '/'), $fileInfoArray['ascii']);
                        $setFlag                                = true;
                        break;
                    } else {
                        $this->_connectionArray[$key]['status'] = @ftp_nb_continue($connection['resource']);
                    }
                }
                if ($setFlag) {
                    break;
                }
            }
        }

        $this->_wait();
    } //}}}

    public function closeFtp() //{{{
    {
        foreach ($this->_connectionArray as $connection) {
            @ftp_close($connection['resource']);
        }
    } //}}}
}
