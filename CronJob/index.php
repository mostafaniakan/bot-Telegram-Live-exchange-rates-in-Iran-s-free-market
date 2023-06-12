<?php


use oop\updateRates;
use oop\users;

include('../scraping.php');
include('../DB/Connection.php');
include_once('../oop/updateRates.php');
include_once('../oop/users.php');

set_time_limit(0);
ob_start();
const token = "6135478668:AAGOHriJ3vZl0XaDy-DlxzjzVxodqhn5hdQ";
$webhook_url = 'https://558f-185-107-81-154.ngrok-free.app';
$db = $db;
$obj = $obj;


$updateRate = new updateRates();
$user = new users();

function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . token . "/" . $method;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    $dataBot = json_decode($res);
    return $dataBot;
}



//set webhook
$url = "https://api.telegram.org/bot".token."/setWebhook?url=$webhook_url";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

if ($result === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Webhook set up successfully';
}
curl_close($ch);

if (php_sapi_name() == 'cli-server') {
    $update = file_get_contents('php://input');
}


$sql = "SELECT `users_id` FROM `users`";
$stmt = $db->query($sql);
$config = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($config as $item) {
    $rate = $user->getUserRates($db, $item['users_id']);

    $checkUser = $user->checkUserId($db, $item['users_id']);
    if ($checkUser > 0) {
        $updateRate->updateRates($db, $rate, $obj, $item['users_id']);
    }
}


