<?php
//////////////////////////Логирование ошибок////////////////////////////////////
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('log_errors', 1);
ini_set('error_log','log.txt');
//////////////////////////Подключение файлов////////////////////////////////////
include_once 'functions/LeaveJoin/leave.php';
include_once 'functions/LeaveJoin/join.php';
include_once 'functions/Menu/sendMenu.php';
include_once 'functions/Messages/mainMessage.php';
include_once 'functions/Messages/messageReply.php';
include_once 'libs/request.php';
//////////////////////////Проверка на запрос////////////////////////////////////
if (!isset($_REQUEST)) { 
  return; 
} 
//////////////////////////Глобальные константы//////////////////////////////////
$confirmation_token = 'c1e42cba'; 
$token = 'd9606ee94a5e439daddfab82ec59b8b4f318f077dc80fbdcdbfe1852fbda2e08b2d6dc3ecf54e1cc5c19b';
$need_menu = true;
//////////////////////////Создание базы данных//////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);
//////////////////////////Получение тела запроса////////////////////////////////
$data = json_decode(file_get_contents('php://input')); 
$user_id = $data->object->user_id; 
$message = $data->object->body;

$user_info = json_decode(file_get_contents(
    "https://api.vk.com/method/users.get?user_ids={$user_id}&v=5.0&lang=ru")); 
$user_name = $user_info->response[0]->first_name;

switch ($data->type) { 
//////////////////////////Подтверждение///////////////////////////////////////
 case 'confirmation':
    echo $confirmation_token;
    break;
//////////////////////////Выход из группы///////////////////////////////////////
 case 'group_leave':
    $response = leave_message($user_id, $connection);
    $need_menu = false;
    break;
//////////////////////////Вступление в группу///////////////////////////////////
 case 'group_join':
    $response = join_message($user_id, $connection);
    break;
 case 'message_new':
    $response = proceed_message($user_id,$message,$connection); 
    $response =  explode('|', $response);
    $photo = $response[1];
    $response = $response[0];
    if($response == "no") return; 
    break;
 case 'message_reply':
    $message = mb_strtolower($message);
    $response = check_reply($user_id, $message, $connection);
    $response = explode('|', $response);
    $user_id = $response[0];
    $response = $response[1];
    if($response == "no") return;
    break;
     
}
//////////////////////////Отправка сообщения////////////////////////////////////
$params = array( 
      'message' => $response,
      'attachment' => $photo,
      'user_id' => $user_id, 
      'access_token' => $token, 
      'v' => '5.69' 
    ); 
POST('https://api.vk.com/method/messages.send?',$params);
if($need_menu) send_menu($user_id, $connection);
$connection->close();
echo ('ok');
?>