<?php
//////////////////////////Функция-обработчик////////////////////////////////////
function check_reply($user_id, $message, $db) {
    
   

    $key = "новый ответ в опросе";
    $res = strripos($message, $key);
    if ($res !== false) {
//////////////////////////Парсинг id///////////////////////////////////////////
        $message_preg =  preg_match_all('/id[0-9]{3,}/', $message, $new_id);
        $new_id = $new_id[0][0];
        $new_id = explode('d', $new_id)[1];
        $db->query(
        "INSERT INTO Blanks (user_id, VK_name, VK_lastname, OS, blank_name, blank_lastname, email) VALUES ($new_id, NULL, NULL, NULL, NULL, NULL, NULL)"
        );
//////////////////////////Парсинг имени-фамилии с ВК////////////////////////////
        $user_info = json_decode(file_get_contents
        ("https://api.vk.com/method/users.get?user_ids={$new_id}&v=5.0&lang=ru")); 
        $user_name = $user_info->response[0]->first_name; 
        $user_lastname = $user_info->response[0]->last_name; 
        $db->query("UPDATE Blanks SET VK_name = '$user_name' WHERE user_id = $new_id");
        $db->query("UPDATE Blanks SET VK_lastname = '$user_lastname' WHERE user_id = $new_id");
//////////////////////////Парсинг OS////////////////////////////////////////////
        $response = "$user_name, Благодарим за заполнение анкеты &#128521;";      
        if(strripos($message, "android")) {
            $response.="<br>Приглашаем вступить в беседу тестеров!
            Вот ссылка на вступление: https://vk.me/join/AJQ1d5nTJQIFKhdewCiZOZ0D";
            $db->query("UPDATE Blanks SET OS = 'Android' WHERE user_id = $new_id");
        }
        else if(strripos($message, "ios")) {
            $response.="<br>Версия для iOS ещё не готова, мы оповестим вас, как только сможем. Спасибо за ожидание!";
            $db->query("UPDATE Blanks SET OS = 'iOS' WHERE user_id = $new_id");
        }
//////////////////////////Парсинг ФИО с анкеты//////////////////////////////////
       $blank = explode('q: ', $message)[1];
       $blank = explode('a: ', $blank)[1];
       $blank = explode(' ', $blank);
       $blank_name = $blank[1];
       $blank_lastname = $blank[0];
       $db->query("UPDATE Blanks SET blank_name = '$blank_name' WHERE user_id = $new_id");
       $db->query("UPDATE Blanks SET blank_lastname = '$blank_lastname' WHERE user_id = $new_id");
//////////////////////////Парсинг email/////////////////////////////////////////
       $exp = explode('q: ', $message)[5];
       $exp = explode('a: ', $exp)[1];
       $db->query("UPDATE Blanks SET email = '$exp' WHERE user_id = $new_id");
//////////////////////////Возврат///////////////////////////////////////////////

    }
     
    else $response = "no";       
    
    return $new_id."|".$response;
}
////////////////////////////////////////////////////////////////////////////////
?>