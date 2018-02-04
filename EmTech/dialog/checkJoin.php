<?php
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Mysqli------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);

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

function check_join($connection) {

$tokenAcc = 'a23cfa3460c6ea707a096c2967195683aa4415755a3157e65367a4a34f13e1225f85daa7adabfbc36b190';
$tokenGroup = 'd9606ee94a5e439daddfab82ec59b8b4f318f077dc80fbdcdbfe1852fbda2e08b2d6dc3ecf54e1cc5c19b';
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Main code---------------------------------*/
/////////////////////////////////////////////////////////////////////////////////

//--Делаем запрос--//
    $url = 'https://api.vk.com/method/messages.getHistory?';
    $params = array(
        'count' => '200',  
        'chat_id' => '1',   
        'access_token' => $tokenAcc,  
        'v' => '5.69',
    );
    $get_params = http_build_query($params);
    $result = GET($url.$get_params);
    $result = $result[response][items];
//-----------------//

//--Смотрим дату последнего оповещения о вступлении в диалог--//
$lastJoinDate = $connection->query("SELECT last_join_date FROM last_date")->fetch_row()[0];
//-----------------------------------------------------------//


//--Сравниваем дату в базе и дату последнего сообщения в диалоге--//
if($result[0][date]>$lastJoinDate){
//----------------------------------------------------------------//

    for($i=0;$i<=200;$i++){
//--Просматриваем кжое сообщение на наличие (вступлния/выхода из диалога)--//
        $user = $result[$i]['action_mid'];
       
        if($user==null)continue;
         
//-------------------------------------------------------------------------//
        $user_level = $connection->query("SELECT level FROM EmTechUsers WHERE user_id=$user")->fetch_row()[0];
        
      
       
        if($result[$i][date]>$lastJoinDate and ($result[$i]['action'] == 'chat_invite_user' or $result[$i]['action'] == 'chat_invite_user_by_link') and $user_level != 'joined_group') {
           
            $request = $connection->query("UPDATE EmTechUsers SET level = 'joined_group' WHERE user_id = $user");
             
        
           
         $respons = "Добро пожаловать в тестирование приложения BusGO.<br>В течение суток Ваш Google Play аккаунт получит доступ к скачиванию Альфа-тестового приложения.<br>Ссылка на приложение и Ваш личный ключ активации будут отправлены вам в течение дня.";
            $request_params = array(
                'message' => $respons,
                'user_id' => $user,
                'access_token' => $tokenGroup,
                'v' => '5.8'
            );
            
            POST('https://api.vk.com/method/messages.send?',$request_params);
            
            $email= $connection->query("SELECT email FROM Blanks WHERE user_id=$user")->fetch_row()[0];
            $connection->query(
            "INSERT INTO Emails (user_id, email) VALUES
            ($user, '$email')"
            );
            
            
        }
    
    }
}

/////////////////////////////////////////////////////////////////////////////////
/*--------------------------------------End------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$lastJoinDate = $result[0][date];
$connection->query("UPDATE last_date SET last_join_date = '$lastJoinDate'");
}



check_join($connection);
?>









 