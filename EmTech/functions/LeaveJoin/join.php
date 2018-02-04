<?php

function join_message($user_id, $db) {
    
   $user = $db->query("SELECT user_id FROM EmTechUsers WHERE user_id = $user_id")->fetch_row();
   if($user==null) {
        $db->query(
        "INSERT INTO EmTechUsers (menu, user_id, access, level) VALUES
        (NULL, '$user_id', 'user', 'new_member')"
        );
      
        $response = "Добро пожаловать в сообщество EmTech &#128526;
        Здесь Вы сможете узнать всё о наших проектах!";
    }
    
    else $response = "Приветствуем Вас вновь в сообществе EmTech &#128526;
        Здесь Вы сможете узнать всё о наших проектах!";
    
    $db->query("UPDATE EmTechUsers SET menu = 'join' WHERE user_id = $user_id");
    return $response;
}


?>