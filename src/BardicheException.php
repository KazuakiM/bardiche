<?php

namespace KazuakiM\Bardiche;

/**
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/bardiche
 */
class BardicheException extends \Exception
{
    public static function getMessageJson(string $message) : string //{{{
    {
        return json_encode([
            'message' => $message,
        ]);
    } //}}}
}
