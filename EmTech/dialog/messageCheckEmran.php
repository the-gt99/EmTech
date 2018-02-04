<?php
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Mysqli------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);

include_once 'userKey.php';
include_once "../libs/request.php";

/////////////////////////////////////////////////////////////////////////////////
/*----------------------------------Log error----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('log_errors', 1);
ini_set('error_log','log.txt');



function check_Em_message($connection){
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Classes-----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////
/*----------------------------------Variables----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$count = '5';
$user_id = '81747790';
$tokenAcc = 'a23cfa3460c6ea707a096c2967195683aa4415755a3157e65367a4a34f13e1225f85daa7adabfbc36b190'; 



/////////////////////////////////////////////////////////////////////////////////
/*------------------------------------Main-------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$lastEmranDate = $connection->query("SELECT last_emran_date FROM last_date")->fetch_row()['0'];

//--Получаем 5 сообщений с юзером--//
    $url ='https://api.vk.com/method/messages.getHistory';
    $params = "count=$count&user_id=$user_id&access_token=$tokenAcc&v=5.71";
    $result = POST($url,$params);
    $result = $result[response][items];
    $flag=0;
    if($result[0][date] > $lastEmranDate){
        for($i=0;$i<count($result);$i++){
            if($result[$i][date] > $lastEmranDate and $result[$i][body] == 'ок') $flag = 1;
        }
    }
    if($flag) messageEmran($connection,$tokenAcc);
    if($flag) {
         $request_params = array(
                'message' => "Успешно.",
                'user_id' => $user_id,
                'access_token' => $tokenAcc,
                'v' => '5.8'
                );
                POST('https://api.vk.com/method/messages.send?',$request_params);
    }
//--------------------------------//

    $lastMessageDate = $result[0][date];
    $connection->query("UPDATE last_date SET last_emran_date = $lastMessageDate");
}

function messageEmran($connection,$tokenAcc){
    $ids = $connection->query("SELECT user_id FROM Emails")->fetch_all();
    for($j=0;$j < count($ids) and $j < 20;$j++){
        $key = get_key($ids[$j][0],$connection);
        $respons = "Вашу почту активировали, вы можете приступить к тестированию. Ваш ключ: $key";
        $request_params = array(
            'message' => $respons,
            'user_id' => $ids[$j][0],
            'access_token' => "d9606ee94a5e439daddfab82ec59b8b4f318f077dc80fbdcdbfe1852fbda2e08b2d6dc3ecf54e1cc5c19b",
            'v' => '5.8'
        );
        POST('https://api.vk.com/method/messages.send?',$request_params);
    }
    
$connection->query("TRUNCATE Emails");
        
}

check_Em_message($connection);
?>