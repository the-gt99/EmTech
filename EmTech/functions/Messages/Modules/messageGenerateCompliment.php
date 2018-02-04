<?php
function compliment($user_id, $db){
    
    $user_info = json_decode(file_get_contents
    ("https://api.vk.com/method/users.get?user_ids={$user_id}&v=5.0&lang=ru")); 
    $user_name = $user_info->response[0]->first_name;
    
    $sex = $db->query("SELECT sex FROM EmTechUsers WHERE user_id=$user_id")->fetch_row()[0];
    if($sex=='male') $sex='1';
    else if($sex=='female') $sex='0';
    
    $long_val = '1';
    $url = 'http://freegenerator.ru/compliment';
    $params = array(
        'type' => 'compliment',
        'sex' => $sex,
        'long_val' => $long_val,
    );

    $result = POST($url,$params);

    $responses = array("😘","😍","😏","☺","😌","😉","😄");
    $ran =  rand(0, (count($responses)-1));
    $smile = $responses[$ran]; 
    
    return $user_name.", ".mb_strtolower($result[text]).$smile;
}

?>