<?php
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Mysqli------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connecton = new mysqli($host, $username, $password, $db);

/////////////////////////////////////////////////////////////////////////////////
/*----------------------------------Variables----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$token = 'd9606ee94a5e439daddfab82ec59b8b4f318f077dc80fbdcdbfe1852fbda2e08b2d6dc3ecf54e1cc5c19b';

/////////////////////////////////////////////////////////////////////////////////
/*----------------------------------Log error----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('log_errors', 1);
ini_set('error_log','log.txt');



/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Classes-----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
include_once "../libs/request.php";


/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Take key----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
function get_key($user_id,$connecton){
//--Смотрим ключ у user(a)--//
    $key_from_base = $connecton->query("SELECT user_key FROM EmTechUsers WHERE user_id ='$user_id'")->fetch_row()[0];
//--------------------------//   

    if($key_from_base == NULL){
//--Получаем ключ,если у user(a) еще нет такового--//
        $request = 'apikey=ZBLdSJKop88KoGdSyvcoZvAqZFYFo0eNrXrI1RGdfGTiUvutkATehj3mqcb4jtHK';
        $url = 'https://emtechgroup.ru/busgo/users/key/';
        $result = POST($url,$request);
        $response = $result[invite];
        $request = $connecton->query("UPDATE EmTechUsers SET user_key = '$response' WHERE user_id = '$user_id'");
//------------------------------------------------//   
    }else{
//--Если у юзера уже есь ключ или он активирован--//    
        if($key_from_base != Null) $response = $key_from_base;
        
        if($key_from_base == 'used') $response = "Вы уже активировали свой ключ!";
    }
//-----------------------------------------------//    
return $response; 
}



/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Test key----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
if($_POST['keyUsed'] == null) return 'error';

//--Ищем user(a) по ключу--//
    $request = $connecton->query("SELECT user_id FROM EmTechUsers WHERE user_key='{$_POST['keyUsed']}'");
    $user_from_base = $request->fetch_row();
    $user_id = $user_from_base[0];
//------------------------//   

    if($user_from_base != null) {
//--Заменяем найденый ключ у пользователя на "used" и оповещаем user(a) об успешной активации--//
        $request = $connecton->query("UPDATE EmTechUsers SET user_key = 'used' WHERE user_id = '$user_id'");
        
        $request_params = array( 
            'message' => 'Ваш ключ был активирован!',
            'user_id' => $user_id, 
            'access_token' => $token, 
            'v' => '5.69' 
        ); 
        POST("https://api.vk.com/method/messages.send",$request_params);
//---------------------------------------------------------------------------------------------//
    }
    
    
    
/////////////////////////////////////////////////////////////////////////////////
/*--------------------------------------End------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
echo('ok');
$connecton->close();
?>