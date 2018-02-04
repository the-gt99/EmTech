<?php
include_once 'functions/Messages/Modules/messageAbout.php';
include_once 'functions/Messages/Modules/messageNews.php';
include_once 'functions/Messages/Modules/messageCompliment.php';
include_once 'functions/Messages/Modules/messageGenerateCompliment.php';
include_once 'functions/Messages/Modules/messageNewsShare.php';
include_once 'functions/Messages/Modules/messageNewsSubscribe.php';
include_once 'functions/Messages/Modules/messageWantsTest.php';
//////////////////////////Функция-роутер////////////////////////////////////////
function proceed_message($user_id,$message,$db) {
    
   $menu = $db->query("SELECT menu FROM EmTechUsers WHERE user_id=$user_id")->fetch_row()[0];
    
    switch($menu) {
        case 'main':
            $response = main_menu($message,$user_id,$db);
            break;
        case 'news':
             $response = news_menu($message,$user_id,$db);
            break;
        case 'about':
             $response = about_menu($message,$user_id,$db);
            break;
        case 'to_main':
            $response = to_main_menu($message,$user_id,$db);
            break;
        case 'test':
            $response = test_menu($message,$user_id,$db);
            break;
        case 'join':
            $response = join_menu($message,$user_id,$db);
            break;
        case 'none':
            $response = "Для диалога с ботом подпишитесь на группу.";
            break;
        case 'choose_sex':
            $response = sex_menu($message,$user_id,$db);
            break;
        case 'again_compliment':
            $response = again_menu($message,$user_id,$db);
            break;
    }
        
    
    return $response;
   
}


//////////////////////////Обработка main menu///////////////////////////////////
function main_menu($message,$user_id,$db) {
      switch($message) {
        case '1':
            $response = message_news($user_id, $db);
            break;
        case '2':
            $response = message_about($user_id, $db);
            break;
        case '3':
            $response = message_wants_test($user_id,$db);
            break;
        case '4':
            $response = compliment_message($user_id,$db);
            break;
        default:
            $response = "Вы ввели неправильную команду.";
      }
      
      return $response;
}
//////////////////////////Обработка news menu///////////////////////////////////  
function news_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
            $response = news_subscribe($user_id,$db);
            break;
        case '2':
             $response = news_share($user_id,$db);
            break;
        case '3':
            $db->query("UPDATE EmTechUsers SET menu = 'main' WHERE user_id = $user_id");
            $response = "Добро пожаловать в главное меню!";
            break;
        default:
            $response = "Вы ввели неправильную команду.";
      }
      
      return $response;
}
//////////////////////////Обработка about menu//////////////////////////////////
function about_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
           $response = message_wants_test($user_id,$db);
            break;
        case '2':
             $db->query("UPDATE EmTechUsers SET menu = 'main' WHERE user_id = $user_id");
             $response = "Добро пожаловать в главное меню!";
            break;
        default:
            $response = "Вы ввели неправильную команду.";
      }
      
      return $response;
}
//////////////////////////Обработка to_main menu////////////////////////////////
function to_main_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
            $db->query("UPDATE EmTechUsers SET menu = 'main' WHERE user_id = $user_id");
            $response = "Добро пожаловать в главное меню!";
            break;
        default:
            $response = "Вы ввели неправильную команду.";
    }
    
    return $response;
}
//////////////////////////Обработка test menu///////////////////////////////////
function test_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
            $response = message_wants_test($user_id,$db);
            break;
        default:
            $response = "Вы ввели неправильную команду.";
    }
    return $response;
}
//////////////////////////Обработка join menu///////////////////////////////////
function join_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
            $db->query("UPDATE EmTechUsers SET menu = 'main' WHERE user_id = $user_id");
            $response = "Добро пожаловать в главное меню!";
            break;
        default:
            $response = "Вы ввели неправильную команду.";
      }
     
    return $response;
}
//////////////////////////Обработка sex menu///////////////////////////////////
function sex_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
            $db->query("UPDATE EmTechUsers SET sex = 'male' WHERE user_id = $user_id");
            $response = compliment($user_id,$db);
            $db->query("UPDATE EmTechUsers SET menu = 'again_compliment' WHERE user_id = $user_id");
            break;
        case '2':
            $db->query("UPDATE EmTechUsers SET sex = 'female' WHERE user_id = $user_id");
            $response = compliment($user_id,$db);
            $db->query("UPDATE EmTechUsers SET menu = 'again_compliment' WHERE user_id = $user_id");
            break;
        default:
            $response = "Вы ввели неправильную команду.";
      }
     
    return $response;
}
//////////////////////////Обработка again menu///////////////////////////////////
function again_menu($message,$user_id,$db) {
    switch($message) {
        case '1':
            $response = compliment($user_id,$db);
            $db->query("UPDATE EmTechUsers SET menu = 'again_compliment' WHERE user_id = $user_id");
            break;
        case '2':
            $db->query("UPDATE EmTechUsers SET menu = 'main' WHERE user_id = $user_id");
            $response = "Добро пожаловать в главное меню!";
            break;
        default:
            $response = "Вы ввели неправильную команду.";
      }
     
    return $response;
}
//////////////////////////Обработка again menu///////////////////////////////////

?>