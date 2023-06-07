<?php
use oop\btn;
use oop\methods;
use oop\updateRates;
use oop\users;

include('DB/Connection.php');
include('scraping.php');
include_once ('oop/updateRates.php');
include_once ('oop/users.php');
include_once ('oop/methods.php');
include_once ('oop/btn.php');

set_time_limit(0);
ob_start();
$user = new users();
$btn = new btn();
$methods = new methods();
$updateRate = new updateRates();


$update = file_get_contents("php://input");

file_put_contents('data.json', $update);

$data = json_decode($update);

const token = "6135478668:AAGOHriJ3vZl0XaDy-DlxzjzVxodqhn5hdQ";


//variable
$db = $db;
$obj = $obj;
$value = "";
$rate = $user->getUserRates($db);
$flag=false;

if (isset($data->message->from->id)) {
    $chat_id = $data->message->from->id;
    $updateArray = json_decode($update, true);

}
if (isset($data->message->text)) {
    $value = $data->message->text;
}

if (isset($data->callback_query->from->id)) {
    $chat_id = $data->callback_query->from->id;
    $updateArray = json_decode($update, true);

}
if (isset($data->callback_query->data)) {
    $value = $data->callback_query->data;
}
if (isset($data->message->from->username)) {
    $username = $data->message->from->username;
}
if (isset($data->message->contact->phone_number)) {
    $phone = $data->message->contact->phone_number;
}


if ($value == '/start') {
    $checkUser = $user->checkUserId($db, $chat_id);
    if ($checkUser == 0) {
        $btn->inlineKeyboards(token, $chat_id, 'Create Account', 'createUser', 'pleas Create Account',);
    }
}


if ($value == 'createUser') {
    if(isset($chat_id)){
        $btn->getPhoneNumber(token, $chat_id);
    }
}

if (isset($phone) ) {
    $user->createAcount($db, $chat_id, $username, $phone);
}


if(isset($chat_id)){
    $checkUser = $user->checkUserId($db, $chat_id);
}

if(isset($checkUser)){
    if ($checkUser > 0) {


        if ($value == 'USD' || $value == 'EUR' || $value == 'GBP' || $value == 'AUD') {

            $sql = "SELECT `id`, `user_id`, `value` FROM `items_users` WHERE `user_id` = $chat_id AND `value` = '$value'";
            $stmt = $db->query($sql);
            $config = $stmt->rowCount();

            if ($config == 0) {
                $sql = "INSERT INTO items_users (user_id, value) VALUES (?,?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$chat_id, $value]);
                $methods->sendmessage($chat_id, 'save');
            }
            for ($i = 0; $i < count($obj); $i++) {

                if ($obj[$i]['Code'] == $value) {
                    for ($x = 0; $x < count($rate); $x++) {
                        if ($rate[$x]['name'] == $value) {
                            $flag=true;
                            if($obj[$i]['Buy'] > $rate[$x]['buy']){
                                $methods->showRateUp($chat_id,$obj,$i);
                                $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                            }else if($obj[$i]['Buy'] < $rate[$x]['buy']){
                                $methods->showRatedown($chat_id,$obj,$i);
                                $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                            }else{
                                $methods->showRate($chat_id,$obj,$i);
                            }
                        }

                    }
                    if(!$flag){
                        $methods->showRate($chat_id,$obj,$i);
                    }
                }

            }
        } else {
            $btn->customKeyboard(token, $chat_id, 'Please select an item');
        }

    }
}

if(isset($chat_id)){
    $checkUser = $user->checkUserId($db, $chat_id);
}
if(isset($checkUser)){
    if ($checkUser > 0) {
        $updateRate->updateRates($db, $rate, $obj, $chat_id);
    }
}

//


