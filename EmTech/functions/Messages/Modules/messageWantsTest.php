<?php
//////////////////////////Функция///////////////////////////////////////////////
function message_wants_test($user_id,$db) {
    
    $os = $db->query("SELECT OS FROM Blanks WHERE user_id=$user_id")->fetch_row()[0];
          $db->query("UPDATE EmTechUsers SET menu = 'to_main' WHERE user_id = $user_id");
    
    
           if($os == 'Android')
           $response = "Вы уже прошли тестирование! Теперь присоединяйтесь к беседе тестеров по ссылке, которую мы прислали Вам выше.|";
           else if($os == 'iOS')
           $response = "Вы уже прошли тестирование! Теперь ожидайте, мы оповестим Вас, как только версия BusGo для iOS будет готова.|";
           else $response = "Предлагаем Вам заполнить анкету и принять участие в тестировании нового приложения “BusGO”. <br>Для этого пройдите по ссылке: https://vk.com/app5619682_-150317679<br>Или нажмите на кнопку “Участие в тестировании” на странице группы:|photo-93149630_456239280";
    
   
         
   return $response; 
}
//////////////////////////Функция///////////////////////////////////////////////

?>