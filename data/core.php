<?php
ob_start('ob_gzhandler');
session_start();
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', FALSE);
ini_set('log_errors', TRUE);
ini_set('error_log', SR_CORE.'errors.log');
if (is_file(SR_CORE.'data/db.ini'))
{
    $db_conf = parse_ini_file(SR_CORE.'data/db.ini');
    define('S_HOST', $db_conf['host']);
    define('S_PORT', $db_conf['port']);
    define('S_DB_NAME', $db_conf['db_name']);
    define('S_USER', $db_conf['user']);
    define('S_PASS', $db_conf['pass']);
    define('S_CHARSET_NAMES', $db_conf['charset_names']);
    include_once SR_CORE.'data/db.php';
} else {
    die('<h1>No connection to the database server!<br />Check the DB-connection file!</h1>');
}



class SR {
    // Фильтры для текста
    public static function Text_Filter($text=NULL)
    {
        // Указываем свои параметры
        return $text;
    }

    // Обработка суммы
    public static function Money_Check($money='0.00')
    {
        // Указываем свои параметры
        return $money;
    }

    // Curl
    public static function Curl($url=NULL, $data=NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // Обработка суммы
    public static function Error_Sort($text=NULL)
    {
        // Указываем свои параметры
        return $text;
    }
}