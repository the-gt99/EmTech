<?php
//////////////////////////Функция///////////////////////////////////////////////
function message_news($user_id, $db) {
    
    $db->query("UPDATE EmTechUsers SET menu = 'news' WHERE user_id = $user_id");
    $response="Спасибо Вам за проявленный интерес!|";
  
   return $response; 
}
?>