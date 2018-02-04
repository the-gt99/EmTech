<?php

function leave_message($user_id, $db) {
      
    $db->query("UPDATE EmTechUsers SET menu = 'none' WHERE user_id = $user_id");
    
    $response = "Стойте, куда Вы? У @emtech (Em.Tech) впереди ещё куча интересного.
    Рекомендую не отписываться &#128527;";
    return $response;
}

?>