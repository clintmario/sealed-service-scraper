<?php
namespace App\Modules\Core\Entities;

use App\Modules\Core\Entities\Log;

class Util
{
    private static $secret_key = "CM_SUPER_SECRET1";
    private static $secret_iv = "CM_SUPER_SECRET_IV1";

    public static function twoWayEncrypt($text, $key = null)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = self::$secret_key;
        $secret_iv = self::$secret_iv;
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = base64_encode(openssl_encrypt($text, $encrypt_method, $key, 0, $iv));
        return $output;
    }

    public static function twoWayDecrypt($text, $key = null)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = self::$secret_key;
        $secret_iv = self::$secret_iv;
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($text), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    public static function postAsync($url, $params, $auth="")
    {
        $errorNumber = -1;
        $errorString = '';

        $postString = http_build_query($params);

        $parts = parse_url($url);
        if (empty($_SERVER['HTTPS']) OR $_SERVER['HTTPS'] === 'off') {
            $host = $parts['host'];
            $port = 80;
        }
        else {
            $host = "ssl://" . $parts['host'];
            $port = 443;
        }

        $fp = fsockopen($host, $port,
            $errorNumber, $errorString, 30);

        $cookie = !empty($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : '';

        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Cookie: " . $cookie . "\r\n";
        $out .= "Referer: " . Config::get('app.url') . "\r\n";

        if(strlen($auth) > 0) {
            $out.= "Authorization: Basic " . base64_encode($auth) . "\r\n";
        }

        $out.= "Keep-Alive: timeout=1000\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($postString)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($postString)) {
            $out .= $postString;
        }

        fwrite($fp, $out);

        stream_set_timeout($fp, 86400);

        return $fp;
    }
}