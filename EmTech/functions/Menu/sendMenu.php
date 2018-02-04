<?php

//////////////////////////Функция///////////////////////////////////////////////

function send_menu($user_id, $db)

{
    
    $menu = $db->query("SELECT menu FROM EmTechUsers WHERE user_id=$user_id")->fetch_row()[0];
    
    switch($menu) {
        case 'to_main':
            $response = "Введите 1&#8419;, чтобы вернуться в главное меню";
            break;
        case 'news':
            $response = "Введите 1&#8419;, если хотите подписаться на нашу ежемесячную рассылку о результатах проекта.<br>Введите 2&#8419;, если хотите поделиться записью из нашего сообщества со своими друзьями<br>Введите 3&#8419;, чтобы вернуться в главное меню";
            break;
        case 'main':
            $response = "Введите 1&#8419;, если хотите подписаться на наши новости<br>Введите 2&#8419;, если хотите более подробно узнать о BusGo<br>Введите 3&#8419;, если хотите принять участие в тестировании<br>Введите 4&#8419;, чтобы получить комплимент от бота";
           
            break;
        case 'about':
            $response = "Введите 1&#8419;, чтобы принять участие в тестировании приложения и внести свой вклад в развитие проекта<br>Введите 2&#8419;, чтобы вернуться в главное меню";
            break;
        case 'join':
            $response = "Введите 1&#8419;, чтобы увидеть наше меню";
            break;
        case 'choose_sex':
            $response = "Введите 1&#8419;, чтобы выбрать мужской пол<br>Введите 2&#8419;, чтобы выбрать женский пол";
            break;
        case 'again_compliment':
             $response = "Введите 1&#8419;, чтобы сгенерировать ещё один комплимент<br>Введите 2&#8419;, вернуться в главное меню";
            break;
    }
    
    $token = "d9606ee94a5e439daddfab82ec59b8b4f318f077dc80fbdcdbfe1852fbda2e08b2d6dc3ecf54e1cc5c19b";
    
    $params = array(
        'user_id' => $user_id,    
        'message' => $response,
        'access_token' => $token,  
        'v' => '5.8',
    );

   POST('https://api.vk.com/method/messages.send?',$params);
}

//////////////////////////Функция///////////////////////////////////////////////

?>

