<?php


use oop\updateRates;
use oop\users;

include('../scraping.php');
include ('../DB/Connection.php');
include_once ('../oop/updateRates.php');
include_once ('../oop/users.php');

set_time_limit(0);
ob_start();



$update = file_get_contents("php://input");

file_put_contents('data.json', $update);

$data = json_decode($update);

const token = "6135478668:AAGOHriJ3vZl0XaDy-DlxzjzVxodqhn5hdQ";


//variable
$db = $db;
$obj = $obj;

$updateRate = new updateRates();
$user = new users();
$rate = $user->getUserRates($db);
print_r($rate);

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


$checkUser = $user->checkUserId($db, $chat_id);
if ($checkUser > 0) {
    $updateRate->updateRates($db, $rate, $obj, $chat_id);
}
