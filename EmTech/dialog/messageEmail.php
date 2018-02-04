<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('log_errors', 1);
ini_set('error_log','log.txt');

include_once "../libs/request.php";

function sendMails() {
    
    $host = "localhost";
    $username = "u148682720_test";
    $password = "egtXOIZTaE1y";
    $db = "u148682720_test";
    $connection = new mysqli($host, $username, $password, $db);   
    
    $mails = $connection->query("SELECT email FROM Emails")->fetch_all();
    for($i=0;$i<count($mails);$i++) {
        $str.=$mails[$i][0]."<br>";
    }
    //if(count($mails==0)) $str="Новых пользователей за сегодня нет.";
    $str = count($mails)." пользователей ожидают потверждения почты:<br>".$str;
    

            $request_params = array(
                'message' => $str,
                'user_id' => "81747790",
                'access_token' => "a23cfa3460c6ea707a096c2967195683aa4415755a3157e65367a4a34f13e1225f85daa7adabfbc36b190",
                'v' => '5.8'
            );
            
    POST('https://api.vk.com/method/messages.send?',$request_params);
    
    
}

sendMails();
?>