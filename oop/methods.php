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
            'parse_mode' => 'MarkDown'
        ]);
        return $data;
    }

    public function showRate($chat_id, $obj, $row)
    {
        $data = $this->sendmessage($chat_id, 'Name : ' . $obj[$row]['Code'] . "=>" . 'Buy : ' . $obj[$row]['Buy'] . "=>" . 'Sell : ' . $obj[$row]['Sell']);
        return $data;
    }

    public function showRateUp($chat_id, $obj, $row)
    {
        $data=$this->sendmessage($chat_id, 'Name : ' . $obj[$row]['Code'] . " => " . 'Buy : ' . $obj[$row]['Buy'] . '🟢' . " => " . 'Sell : ' . $obj[$row]['Sell'] . '🔴');
    return $data;
    }

    public function showRateDown($chat_id, $obj, $row)
    {
        $data=$this->sendmessage($chat_id, 'Name : ' . $obj[$row]['Code'] . " => " . 'Buy : ' . $obj[$row]['Buy'] . '🔴' . " => " . 'Sell : ' . $obj[$row]['Sell'] . '🟢');

    return $data;
    }


    public function updateText($chat_id, $message_id, $text)
    {
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'text' => $text,
            'message_id' => $message_id
        ]);
    }

    public function updateButtonId($db, $message_id)
    {
        $sql = "UPDATE `button_id` SET `button_id`='$message_id' WHERE `id` = 1";
        $stmt = $db->query($sql);
        $config = $stmt->rowCount();
        return $config;
    }

    public function getButtonId($db)
    {
        $sql = "SELECT `id`, `button_id` FROM `button_id` WHERE `id`=1;";
        $stmt = $db->query($sql);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        return $config['button_id'];
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


    public function insertMessageId($db, $message_id)
    {
        $sql = "INSERT INTO `message_id`( `message_id`) VALUES ('$message_id')";
        $stmt = $db->query($sql);
        $config = $stmt->rowCount();
        return $config;
    }
}