<?php
//////////////////////////Логирование ошибок////////////////////////////////////
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Classes-----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
include "../libs/request.php";

/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Mysqli------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);

function generate_report($connection) {
$tokenAcc = 'a23cfa3460c6ea707a096c2967195683aa4415755a3157e65367a4a34f13e1225f85daa7adabfbc36b190';    
$count = 200;

$apikey = "b20e914d048a15d49e141960106ab347";
$apitoken = "3ceedce72ab61ac8c46f2e07aacb4a462fd2ed010b56f273c4341340093bfb4a";
$list_id = "5a765676b8a577c56eca5646";

//////////////////////////Шлем запрос///////////////////////////////////////////
    $url = 'https://api.vk.com/method/messages.getHistory';
    $params= "count=200&peer_id=2000000001&access_token=$tokenAcc&v=5.71";
    $result = POST($url,$params);
    $result = $result[response][items];
    
//////////////////////////Сортируем/////////////////////////////////////////////
$request = $connection->query("SELECT last_message_date FROM last_date");
$own = $request->fetch_row();
$lastMessageDate = $own[0];
$connection->query("UPDATE last_date SET last_message_date = ".$result[0][date]);
///Если дата последнего сообщения равна дате что есть у нас, значит новых сообщений не было и парсить смысла нет.
if($result[0][date]>$lastMessageDate){
    
    for($i=0;$i<=200;$i++){

        if($result[$i][date]>$lastMessageDate and strripos($result[$i][body],'id468593975')>0) {

            $id = $result[$i][from_id];
            //$request = $connection->query("SELECT rating FROM EmTechUsers WHERE user_id = $id");
            //$own = $request->fetch_row();
            //$rating = $own[0];
            //$rating++;
            
            //$request = $connection->query("UPDATE EmTechUsers SET rating = $rating WHERE user_id = $id");
                
            if(strripos($result[$i][body],'плох')!==false){
                $card_name = "Report date:".gmdate('H:i:s d.m.y',time()+10800);
                $desc = substr($result[$i][body], 17);
                
                $url = 'https://api.trello.com/1/cards';
                $params = "key=$apikey&token=$apitoken&name=$card_name&idList=$list_id&labels=red&desc=$desc";
                $CardId = POST($url,$params);
                
                $CardId = $CardId['id'];
                $url = "https://api.trello.com/1/cards/$CardId/attachments";
                $urlPhotoReports = vkAttachments($result[$i]);
                print_r($urlPhotoReports);
                for($k=0;$k<5 and $k<count($urlPhotoReports);$k++){
                    $params = "key=$apikey&token=$apitoken&name=Photo&url=".$urlPhotoReports[$k];
                    POST($url,$params);
               }
            }
        }
    }
}
////////////////////////////////////////////////////////////////////////////////
//$lastMessageDate = $result[0][date];
//$connection->query("UPDATE last_date SET last_message_date = '$lastMessageDate'");
}

function vkAttachments($result){
$urls = [];
$attachs = $result['attachments'];
for($i=0;$i<count($attachs);$i++) {
    if($attachs[$i][type]!=='photo') continue;
    $photo = $attachs[$i][photo];
    $keys = array_keys($photo);
    for($j=0;$j<count($keys);$j++) {
        if(strripos($keys[$j],'photo_')!==false) $need = $keys[$j];
    }
    array_push($urls,$photo[$need]);
}
   
return $urls;
}


echo(generate_report($connection));
?>
