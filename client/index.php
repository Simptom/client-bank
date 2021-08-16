<?
/*
    Проверим и вашу обознанность в данной теме)
*/
define('SR_CORE', $_SERVER['DOCUMENT_ROOT'].'/');
include_once SR_CORE.'data/core.php';


// Данные для клиентской стороны (площадка "Test 1")
$client_bank = [
    'platform_id' => 1,
    'open_key' => 'mslrYftbPPifCfWMVqxv3H',
    'secret_key' => '9rrLkAtifxHnbSwKBu95XWJt'
];

// Сумма платежа
$money = '100.00';

// Записываем в базу
DB::$pdo->query("INSERT INTO `client_data` (`money`, `time`) VALUES (?, ?);", array($money, time()));
$id_data = DB::$pdo->lastInsertId();

// Hash-сумма
$hash = md5($client_bank['platform_id'].$money.$client_bank['secret_key'].$id_data);
?>
<form class="mess" method="post" action="/bank/index.php">
    Сумма:<br />
    <input name="money" type="text" value="<?echo $money;?>"/> UAH<br />
    <input name="data_id" type="hidden" value="<?echo $id_data;?>" />
    <input name="platform_id" type="hidden" value="<?echo $client_bank['platform_id'];?>" />
    <input name="open_key" type="hidden" value="<?echo $client_bank['open_key'];?>" />
    <input name="hash" type="hidden" value="<?echo $hash;?>" />
    <input type="submit" value="Продолжить" />
</form>