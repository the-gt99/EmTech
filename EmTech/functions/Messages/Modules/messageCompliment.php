<?php
//////////////////////////Функция///////////////////////////////////////////////
function compliment_message($user_id, $db) {
    
     $db->query("UPDATE EmTechUsers SET menu = 'choose_sex' WHERE user_id = $user_id");
     $response="Выберите пол, для которого бот сделает комплимент:";
  
   return $response; 
}
//////////////////////////Функция///////////////////////////////////////////////
?>