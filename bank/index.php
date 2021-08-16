<?php
define('SR_CORE', $_SERVER['DOCUMENT_ROOT'].'/');
include_once SR_CORE.'data/core.php';


// Проверяем наличие данных

// ID площадки
if (isset($_POST['platform_id']) && SR::Text_Filter($_POST['platform_id']) != NULL && is_numeric($_POST['platform_id']) && $_POST['platform_id'] > 0)
{
    $platform_id = abs(intval($_POST['platform_id']));
} else {
    $platform_id = 0;
}

// ID записи у клиента
if (isset($_POST['data_id']) && SR::Text_Filter($_POST['data_id']) != NULL && is_numeric($_POST['data_id']) && $_POST['data_id'] > 0)
{
    $data_id = abs(intval($_POST['data_id']));
} else {
    $data_id = 0;
}

// Публичный ключ
if (isset($_POST['open_key']) && SR::Text_Filter($_POST['open_key']) != NULL)
{
    $open_key = SR::Text_Filter($_POST['open_key']);
} else {
    $open_key = NULL;
}

// Сумма платежа
if (isset($_POST['money']) && SR::Text_Filter($_POST['money']) != NULL)
{
    $money = SR::Text_Filter($_POST['money']);
} else {
    $money = '0.00';
}


// Hash
if (isset($_POST['hash']) && SR::Text_Filter($_POST['hash']) != NULL)
{
    $hash = SR::Text_Filter($_POST['hash']);
} else {
    $hash = NULL;
}



// Проверка на наличие ошибок
if ($platform_id <= 0)
{
    $error = 'error 1';
}
else if ($data_id <= 0)
{
    $error = 'error 2';
}
else if ($open_key == NULL)
{
    $error = 'error 3';
}
else if (SR::Money_Check($money) == '0.00')
{
    $error = 'error 4';
}
else if (DB::$pdo->querySingle("SELECT COUNT(*) FROM `bank_platform` WHERE `id` = ? AND `open_key` = ?", array($platform_id, $open_key)) == 0)
{
    $error = 'error 5';
} else {
    // Получаем данные платформы
    $platform = DB::$pdo->queryFetch("SELECT * FROM `bank_platform` WHERE `id` = ? AND `open_key` = ? LIMIT 1;", array($platform_id, $open_key));
    if ($platform['status'] == 0)
    {
        // Если платформа не активирована
        $error = 'error 6';
    }
    else if ($platform['status'] == 2)
    {
        // Если платформа не активирована
        $error = 'error 7';
    }
    else if ($hash != md5($platform['id'].$money.$platform['secret_key'].$data_id))
    {
        // Если хеши не совпадают
        $error = 'error 8';
    } else {
        // Если хеши совпадают


        // Отправляем запрос по указанному URL Result
        $result_url = SR::Text_Filter($platform['url_result']);
        $result_data = 'platform_id='.$platform['id'].'&money='.$money.'&open_key='.$platform['open_key'].'&secret_key='.$platform['secret_key'].'&data_id='.$data_id.'&hash='.md5($platform['id'].$money.$platform['secret_key'].$data_id);
        $result = SR::Curl($result_url, $result_data);
        if (strpos($result, 'error') !== false)
        {
            // Определяем тип ошибки
            $error = SR::Error_Sort($result);
            $error_result = true;
        }
        /*
            Проводим снятие и начисление необходимой суммы
            ...
        */
    }
}
if (isset($error))
{
    if (isset($error_result))
    {
        // Если ошибка на стороне клиента (в URL Result), перенаправляем на URL Error
        header('Location: '.SR::Text_Filter($platform['url_error']));
    } else {
        // Если ошибка у нас, выводим текст ошибки
        echo $error;
    }
} else {
    // Записываем результат в базу
    DB::$pdo->query("INSERT INTO `bank_data` (`id_data`, `money`, `time`) VALUES (?, ?, ?);", array($platform['id'], $money, time()));
    // Перенаправляем на указанный URL Success
    header('Location: '.SR::Text_Filter($platform['url_success']));
}
exit;