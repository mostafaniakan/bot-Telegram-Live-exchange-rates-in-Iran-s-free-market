<?php
include('DB/Conection.php');
include ('scraping.php');

set_time_limit(0);
ob_start();
const token = "6135478668:AAGOHriJ3vZl0XaDy-DlxzjzVxodqhn5hdQ";
function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . token . "/" . $method;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        json_decode($res);
    }
}

function sendmessage($chat_id, $text)
{
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'MarkDown'
    ]);
}


$update = file_get_contents("php://input");

file_put_contents('data.json', $update);

$data = json_decode($update);

$chat_id = $data->message->from->id;

$updateArray = json_decode($update, true);
$chatId = $updateArray["message"]["chat"]["id"];

// Defining the keyboard layout
$keyboard = [
    'keyboard' => [
        ['USD', 'EUR'],
        ['GBP', 'AUD'],
    ],
    'resize_keyboard' => true,
    'one_time_keyboard' => false,
];

$encodedKeyboard = json_encode($keyboard);


$value = $data->message->text;

if ($value == '/start') {
    $sql = "SELECT `users_id`, `name` FROM `users` WHERE `users_id` = $chat_id";
    $stmt = $db->query($sql);
    $config=$stmt->rowCount();

   if($config > 0){
       $message = "select item : ";
       file_get_contents("https://api.telegram.org/bot" . token . "/sendMessage?chat_id=" . $chatId . "&text=" . $message . "&reply_markup=" . $encodedKeyboard);
   }else{
       sendmessage($chat_id, 'welcome');
       $sql = "INSERT INTO users (users_id, name) VALUES (?,?)";
       $stmt = $db->prepare($sql);
       $stmt->execute([$chat_id,$data->message->from->username, ]);
       $message = "select item : ";
       file_get_contents("https://api.telegram.org/bot" . token . "/sendMessage?chat_id=" . $chatId . "&text=" . $message . "&reply_markup=" . $encodedKeyboard);
   }

} elseif (strtoupper($value) == 'USD' || strtoupper($value) == 'EUR' || strtoupper($value) == 'GBP' || strtoupper($value) == 'AUD') {

    $sql = "SELECT `id`, `user_id`, `value` FROM `items_users` WHERE `user_id` = $chat_id AND `value` = '$value'";
    $stmt = $db->query($sql);
    $config=$stmt->rowCount();

    if($config == 0 ){
        $sql = "INSERT INTO items_users (user_id, value) VALUES (?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$chat_id, strtoupper($value)]);
        sendmessage($chat_id, 'salam');
    }else{
        $message = "chosen";
        file_get_contents("https://api.telegram.org/bot" . token . "/sendMessage?chat_id=" . $chatId . "&text=" . $message . "&reply_markup=" . $encodedKeyboard);
    }

} else {
    $message = "Please select an item";
    file_get_contents("https://api.telegram.org/bot" . token . "/sendMessage?chat_id=" . $chatId . "&text=" . $message . "&reply_markup=" . $encodedKeyboard);
}
