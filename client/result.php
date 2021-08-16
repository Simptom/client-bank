<?php
define('SR_CORE', $_SERVER['DOCUMENT_ROOT'].'/');
include_once SR_CORE.'data/core.php';


// Данные для клиентской стороны (площадка "Test 1")
$client_bank = [
    'platform_id' => 1,
    'open_key' => 'mslrYftbPPifCfWMVqxv3H',
    'secret_key' => '9rrLkAtifxHnbSwKBu95XWJt'
];


// Проверяем наличие данных
if (!isset($_POST['platform_id']) || SR::Text_Filter($_POST['platform_id']) == NULL || $_POST['platform_id'] != $client_bank['platform_id'])
{
    $res_text = 'error 1';
}
else if (!isset($_POST['open_key']) || SR::Text_Filter($_POST['open_key']) == NULL || $_POST['open_key'] != $client_bank['open_key'])
{
    $res_text = 'error 2';
}
else if (!isset($_POST['data_id']) || SR::Text_Filter($_POST['data_id']) == NULL || DB::$pdo->querySingle("SELECT COUNT(*) FROM `client_data` WHERE `id` = ?", array(abs(intval($_POST['data_id'])))) == 0)
{
    $res_text = 'error 3';
} else {
    $data = DB::$pdo->queryFetch("SELECT * FROM `client_data` WHERE `id` = ? LIMIT 1;", array(abs(intval($_POST['data_id']))));
    if (!isset($_POST['money']) || SR::Text_Filter($_POST['money']) == NULL || $_POST['money'] != SR::Text_Filter($data['money']))
    {
        $res_text = 'error 4';
    }
    else if (!isset($_POST['hash']) || SR::Text_Filter($_POST['hash']) == NULL || $_POST['hash'] != md5($client_bank['platform_id'].SR::Text_Filter($data['money']).$client_bank['secret_key'].$data['id']))
    {
        $res_text = 'error 5';
    } else {
        $res_text = 'OK';
        // Обновляем данные (записываем time() как статус)
        DB::$pdo->query("UPDATE `client_data` SET `status` = ? WHERE `id` = ? LIMIT 1", array(time(), $data['id']));
    }
}
echo $res_text;