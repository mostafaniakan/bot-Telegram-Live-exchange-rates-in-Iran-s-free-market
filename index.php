<?php

//CONFIG
use oop\btn;
use oop\methods;
use oop\updateRates;
use oop\users;

//connection
include('DB/Connection.php');
include('scraping.php');
include_once('oop/updateRates.php');
include_once('oop/users.php');
include_once('oop/methods.php');
include_once('oop/btn.php');

set_time_limit(0);
ob_start();
$user = new users();
$btn = new btn();
$methods = new methods();
$updateRate = new updateRates();


$update = file_get_contents("php://input");

file_put_contents('data.json', $update);

$data = json_decode($update);

const token = "6186515390:AAFS1nPB-WhTE5HSxQf1ETPo4FZtFGfkHPA";
$webhook_url = 'https://abb1-185-107-81-150.ngrok-free.app';

//config bot
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
$url = "https://api.telegram.org/bot" . token . "/setWebhook?url=$webhook_url";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

//if ($result === false) {
//    echo 'Error: ' . curl_error($ch);
//} else {
//    echo 'Webhook set up successfully';
//}
curl_close($ch);

if (php_sapi_name() == 'cli-server') {
    $update = file_get_contents('php://input');
}


//get user id
$chat_id = 0;
if (isset($data->callback_query->from->id)) {
    $chat_id = $data->callback_query->from->id;
    $updateArray = json_decode($update, true);
}

//variable
$db = $db;
$obj = $obj;
$value = "";

$flag = false;

//get user id
if (isset($data->message->from->id)) {
    $chat_id = $data->message->from->id;
    $updateArray = json_decode($update, true);
}

$rate = $user->getUserRates($db, $chat_id);

if (isset($data->message->text)) {
    $value = $data->message->text;
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


////channel
$status = "";
if (isset($data->my_chat_member->chat->id)) {
    $channelId = $data->my_chat_member->chat->id;
    $channelName = $data->my_chat_member->chat->username;
    $getChannel = $methods->getChannel($db, $channelId);

    if ($getChannel == null) {
            $methods->insertChannel($db, $channelId, $channelName);
    }

    $methods->sendmessage($channelId, '<pre>
به ربات CurrencyScan خوش امدین 
برا استفاده از ربات شما باید
فرمت زیر را رعایت کنید به عنوان مثال
             bot_USD
             
</pre>');
}


if (isset($data->channel_post->sender_chat->id)) {
    $channelId = $data->channel_post->sender_chat->id;
}

if (isset($data->my_chat_member->new_chat_member->status)) {
    $status = $data->my_chat_member->new_chat_member->status;
}

if ($status == 'left' || $status == 'kicked') {

    $methods->deleteChannel($db, $channelId);
}

$channelMessage = "";
if (isset($data->channel_post->text)) {
    $channelMessage = $data->channel_post->text;
}
$channelMessage = strtoupper($channelMessage);

if (strpos($channelMessage, "BOT_") !== false) {

    $value = str_replace("BOT_", "", $channelMessage);

    $flag=false;
    foreach ($obj as $item) {
        if ($item['Code'] == $value) {
            $flag=true;
            $methods->sendmessage($channelId, 'NAME : ' . $item['Code'] . "=>" . 'BUY : ' . $item['Buy'] . "=>" . 'SELL : ' . $item['Sell']);
        }
    }
    if(!$flag){
        $methods->sendmessage($channelId,'NOT FUND');
    }
}


//set webhook
if ($value == '/start') {
    $userID = $user->checkUserId($db, $chat_id);
    if ($userID == 0) {
        $language = $methods->getLanguageFirst($db, $chat_id);
        if ($language == null) {
            $btn->selectLanguageFirst($db, $chat_id);
        }

    } else {
        $language = $methods->getLanguage($db, $chat_id);
    }

}

if ($value == "firstENGLISH" || $value == 'firstPERSIAN') {

    $value = str_replace("first", "", $value);
    $language = $value;
    $methods->insertLanguageFirst($db, $chat_id, $language);

}


if (isset($language) && $language == 'ENGLISH') {

    $checkUser = $user->checkUserId($db, $chat_id);

    if ($checkUser == 0) {

        $text = $methods->sendmessage($chat_id, '
    <pre>Welcome to the robot for viewing the live
exchange rate in Iran s free market 🌹
    </pre>');

        $btn->inlineKeyboards($chat_id, 'Register', 'createUser', 'Please register 👇 ',);
    } else {
        $btn->customKeyboard($db, $chat_id, '<pre>
In this section you can choose

Each currency has its current price view

And to automatically send currency prices

You can change your currency from the SETTING⚙️  section

Choose
</pre>');
    }


} else if (isset($language) && $language == 'PERSIAN') {

    $checkUser = $user->checkUserId($db, $chat_id);
    if ($checkUser == 0) {

        $text = $methods->sendmessage($chat_id, '
    <pre>به ربات مشاهده نرخ زنده
ارزدر بازار آزاد ایران خوش آمدین 🌹
    </pre>');

        $btn->inlineKeyboards($chat_id, 'ثبت نام', 'createUser', 'لطفا ثبت نام کنید👇 ',);
    } else {
        $btn->customKeyboard($db, $chat_id, '<pre>
در این قسمت شما میتوانید با انتخاب

 هر ارز قیمت لحظه ای آن را

 مشاهده کنید.

و برای ارسال خودکار قیمت ارز

میتوانید از قسمت setting ⚙️ ارز خود

را انتخاب کنید
</pre>');
    }
}


if ($value == 'createUser') {

    $language = $methods->getLanguageFirst($db, $chat_id);
    if ($language == 'ENGLISH') {
        if (isset($chat_id)) {
            $btn->getPhoneNumber('<pre>In this section, <b>select Share Contact</b> to complete your authentication

👇
</pre> ', $chat_id);
        }
    } elseif ($language == 'PERSIAN') {
        if (isset($chat_id)) {
            $btn->getPhoneNumber('<pre>در این قسمت Share Contact را انتخاب کنید تا احراز هویت شما تکمیل شود

👇
</pre> ', $chat_id);
        }
    }
}

if (isset($phone)) {
    $language = $methods->getLanguageFirst($db, $chat_id);
    $user->createAcount($db, $chat_id, $username, $phone, $language);

    if ($language == 'ENGLISH') {
        $btn->customKeyboard($db, $chat_id, '
<pre>
             🌹🌹🌼🌹🌹

Thank you for choosing us

In this section you can choose

Each currency has its current price

view

And to automatically send currency prices

You can change your currency from the SETTING⚙️ section

Choose
</pre>'
        );
    } else if ($language == 'PERSIAN') {

        $btn->customKeyboard($db, $chat_id, '
<pre>
             🌹🌹🌼🌹🌹

سپاس که مارو انتخاب کردین

در این قسمت شما میتوانید با انتخاب

 هر ارز قیمت لحظه ای آن را

 مشاهده کنید.

و برای ارسال خودکار قیمت ارز

میتوانید از قسمت setting ⚙️ ارز خود

را انتخاب کنید
</pre>'
        );
    }


}

if (isset($chat_id)) {
    $checkUser = $user->checkUserId($db, $chat_id);
}

if (isset($checkUser)) {

    if ($checkUser > 0) {

        $value = trim($value, '⚙️');

        if ($value == 'USD' || $value == 'EUR' || $value == 'GBP' || $value == 'AUD' || $value == 'setting') {

            for ($i = 0; $i < count($obj); $i++) {

                if ($obj[$i]['Code'] == $value) {
                    for ($x = 0; $x < count($rate); $x++) {
                        if ($rate[$x]['name'] == $value) {
                            $flag = true;
                            if ($obj[$i]['Buy'] > $rate[$x]['buy']) {

                                $methods->showRateUp($db, $chat_id, $obj, $i);
                                $getRateId = $user->getUserRates($db, $chat_id);
                                $user->updateRate($db, $getRateId[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);

                            } else if ($obj[$i]['Buy'] < $rate[$x]['buy']) {

                                $methods->showRateDown($db, $chat_id, $obj, $i);
                                $getRateId = $user->getUserRates($db, $chat_id);
                                $user->updateRate($db, $getRateId[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);

                            } else {
                                $methods->showRate($db, $chat_id, $obj, $i);
                                $getRateId = $user->getUserRates($db, $chat_id);
                                $user->updateRate($db, $getRateId[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                            }
                        }
                    }
                    if (!$flag) {
                        $methods->showRate($db, $chat_id, $obj, $i);
                    }
                }
            }
        }
    }
}

if ($value == 'setting' || $value == 'تنظیمات') {
    $btn->SettingradioButton($db, $chat_id);
}

if ($value == "AddRate") {
    $language = $methods->getLanguage($db, $chat_id);
    $button_id = $methods->getButtonId($db);
    if ($language == 'ENGLISH') {

        $btn->AddradioButton($db, $chat_id, $button_id, '<b><i>SELECT✔  OR DELETE</i></b>');
    } else if ('PERSIAN') {
        $btn->AddradioButton($db, $chat_id, $button_id, '<b><i>انتخاب✔  و  حذف</i></b>');
    }
}


if ($value == 'AddUSD' || $value == 'AddEUR' || $value == 'AddGBP' || $value == 'AddAUD') {

    $value = str_replace("Add", "", $value);
    $sql = "SELECT  `user_id`, `value` FROM `items_users` WHERE `user_id`=$chat_id AND `value`='$value'";
    $stmt = $db->query($sql);
    $config = $stmt->rowCount();

    if ($config == 0) {

        $sql = "INSERT INTO `items_users`(`user_id`, `value`) VALUES ('$chat_id','$value')";
        $stmt = $db->query($sql);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);

    }

    $language = $methods->getLanguage($db, $chat_id);
    $button_id = $methods->getButtonId($db);
    if ($language == 'ENGLISH') {
        $btn->AddradioButton($db, $chat_id, $button_id, '<b><i>SELECT✔  OR DELETE</i></b>');
    } else if ('PERSIAN') {
        $btn->AddradioButton($db, $chat_id, $button_id, '<b><i>انتخاب✔  و  حذف</i></b>');
    }
}

if ($value == 'DeleteUSD' || $value == 'DeleteEUR' || $value == 'DeleteGBP' || $value == 'DeleteAUD') {

    $value = str_replace("Delete", "", $value);
    $sql = "SELECT  `id`,`user_id`, `value` FROM `items_users` WHERE `user_id`=$chat_id AND `value`='$value'";
    $stmt = $db->query($sql);
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $config['id'];

    $sql = "DELETE FROM `items_users` WHERE `id` = $id";
    $stmt = $db->query($sql);
    $config = $stmt->rowCount();

    $language = $methods->getLanguage($db, $chat_id);
    $button_id = $methods->getButtonId($db);
    if ($language == 'ENGLISH') {
        $btn->AddradioButton($db, $chat_id, $button_id, '<b><i>SELECT✔  OR DELETE</i></b>');
    } else if ('PERSIAN') {
        $btn->AddradioButton($db, $chat_id, $button_id, '<b><i>انتخاب✔  و  حذف</i></b>');
    }
}

if ($value == 'Selecting Language') {

    $button_id = $methods->getButtonId($db);
    $language = $methods->getLanguage($db, $chat_id);
    if ($language == 'ENGLISH') {
        $btn->selectLanguage($chat_id, '<pre>Please select your language</pre>', $button_id);
    } else if ('PERSIAN') {
        $btn->selectLanguage($chat_id, '<pre>لطفا زبان خودرا انتخاب کنید</pre>', $button_id);
    }
}

if ($value == 'AddENGLISH' || $value == 'AddPERSIAN') {
    $methods = new methods();

    $value = str_replace("Add", "", $value);
    $updateLanguage = $methods->updateLanguage($db, $chat_id, $value);

    if ($updateLanguage == 1) {
        $id = $methods->getButtonId($db);
        $btn->languageNotif($db, $chat_id, $id);
        $language = $methods->getLanguage($db, $chat_id);
        if ($language == 'ENGLISH') {
            $btn->customKeyboard($db, $chat_id, 'ENGLISH');
        } else if ($language == 'PERSIAN') {
            $btn->customKeyboard($db, $chat_id, 'فارسی');
        }
    }
}




//if (isset($chat_id)) {
//    $checkUser = $user->checkUserId($db, $chat_id);
//}
//if (isset($checkUser)) {
//    if ($checkUser > 0) {
//        $updateRate->updateRates($db, $rate, $obj, $chat_id);
//    }
//}
//
//


