<?php

namespace oop;

use PDO;

class methods
{


    public function sendmessage($chat_id, $text)
    {
        $data = bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'html'
        ]);
        return $data;
    }

    public function showRate($db, $chat_id, $obj, $row)
    {
        $methods = new methods();
        $language = $methods->getLanguage($db, $chat_id);
        if ($language == 'ENGLISH') {
            $data = $this->sendmessage($chat_id, 'NAME : ' . $obj[$row]['Code'] . "=>" . 'BUY : ' . $obj[$row]['Buy'] . "=>" . 'SELL : ' . $obj[$row]['Sell']);
        } elseif ($language == 'PERSIAN') {
            $data = $this->sendmessage($chat_id, 'نام : ' . $obj[$row]['Code'] . "=>" . 'خرید : ' . $obj[$row]['Buy'] . "=>" . 'فروش : ' . $obj[$row]['Sell']);
        }
        return $data;
    }

    public function showRateUp($db, $chat_id, $obj, $row)
    {
        $methods = new methods();
        $language = $methods->getLanguage($db, $chat_id);

        if ($language == 'ENGLISH') {
            $data = $this->sendmessage($chat_id, 'NAME : ' . $obj[$row]['Code'] . " => " . 'BUY : ' . $obj[$row]['Buy'] . '🟢' . " => " . 'SELL : ' . $obj[$row]['Sell'] . '🔴');

        } else if ('PERSIAN') {
            $data = $this->sendmessage($chat_id, 'نام : ' . $obj[$row]['Code'] . " => " . 'خرید : ' . $obj[$row]['Buy'] . '🟢' . " => " . 'فروش : ' . $obj[$row]['Sell'] . '🔴');

        }
        return $data;
    }

    public function showRateDown($db, $chat_id, $obj, $row)
    {
        $methods = new methods();
        $language = $methods->getLanguage($db, $chat_id);
        if ($language == 'ENGLISH') {
            $data = $this->sendmessage($chat_id, 'NAME : ' . $obj[$row]['Code'] . " => " . 'BUY : ' . $obj[$row]['Buy'] . '🔴' . " => " . 'SELL : ' . $obj[$row]['Sell'] . '🟢');

        } else if ($language == 'PERSIAN') {
            $data = $this->sendmessage($chat_id, 'نام : ' . $obj[$row]['Code'] . " => " . 'خرید : ' . $obj[$row]['Buy'] . '🔴' . " => " . 'فروش : ' . $obj[$row]['Sell'] . '🟢');

        }
        return $data;
    }


    public function updateButtonIdSetting($db, $message_id)
    {
        $sqlCheck = "SELECT `id`, `button_id`, `type` FROM `button_id` WHERE `type`='add'";
        $configDbBtn = $db->query($sqlCheck);
        $rowCountBtn = $configDbBtn->rowCount();
        print_r($rowCountBtn);


        if ($rowCountBtn > 0) {
            $sql = "UPDATE `button_id` SET `button_id`='$message_id' WHERE `type`='add'";
            $stmt = $db->query($sql);
            $config = $stmt->rowCount();
            return $config;

        } else {
            $insertBtnQuery = "INSERT INTO `button_id`( `button_id`, `type`) VALUES ('$message_id','add')";
            $config = $db->query($insertBtnQuery);
        }
    }

    public function getButtonId($db)
    {
        $sql = "SELECT `id`, `button_id`, `type` FROM `button_id` WHERE `type`='add'";
        $stmt = $db->query($sql);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        return $config['button_id'];
    }

    public function updateText($chat_id, $message_id, $text)
    {
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'text' => $text,
            'message_id' => $message_id
        ]);
    }

    public function updateMessageId($db, $message_id)
    {
        $sql = "UPDATE `message_id` SET `message_id`='$message_id' WHERE `id` = 1";
        $stmt = $db->query($sql);
        $config = $stmt->rowCount();
        return $config;
    }

    public function getMessageId($db)
    {
        $sql = "SELECT `id`, `message_id` FROM `message_id` WHERE `id`=1;";
        $stmt = $db->query($sql);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        return $config['message_id'];
    }

    public function deleteMesage($chat_id,$message_id){
        bot('deleteMessage',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
        ]);
    }

    public function insertMessageId($db, $message_id)
    {
        $sql = "INSERT INTO `message_id`( `message_id`) VALUES ('$message_id')";
        $stmt = $db->query($sql);
        $config = $stmt->rowCount();
        return $config;
    }

    public function updateLanguage($db, $chat_id, $language)
    {
        $languageQuery = "UPDATE `users` SET `Language`='$language' WHERE `users_id` = '$chat_id'";
        $config = $db->query($languageQuery);
        $rowCOUNT = $config->rowCount();
        return $rowCOUNT;
    }

    public function getLanguage($db, $chat_id)
    {
        $query = "SELECT `Language` FROM `users` WHERE `users_id`= '$chat_id'";
        $stmt = $db->query($query);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        return $config['Language'];
    }

    public function insertLanguageFirst($db, $chat_id, $language)
    {
        $query = "INSERT INTO `user_language`( `language`, `user_id`) VALUES ('$language',$chat_id)";
        $stmt = $db->query($query);
        $config = $stmt->rowCount();
        return $config;
    }

    public function getLanguageFirst($db, $chat_id)
    {
        $query = "SELECT `language` FROM `user_language` WHERE `user_id` = '$chat_id'";
        $stmt = $db->query($query);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        return $config['language'];
    }

}